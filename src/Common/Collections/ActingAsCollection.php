<?php

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageTranslation;

trait ActingAsCollection
{
    private static $availableCollections;

    protected static function bootActingAsCollection()
    {
        static::addGlobalScope(static::globalCollectionScope());
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(static::class, $this->id);
    }

    /**
     * Details of the collection such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     */
    public function collectionDetails(): CollectionDetails
    {
        $collectionKey = $this->collectionKey();

        return new CollectionDetails(
            $collectionKey,
            static::class,
            ucfirst(str_singular($collectionKey)),
            ucfirst(str_plural($collectionKey)),
            $this->flatReferenceLabel()
        );
    }

    /**
     * Custom build for new Collections where we convert any models to the correct collection types.
     * Magic override warning.
     *
     * @ref \Illuminate\Database\Eloquent\Model::newCollection()
     *
     * @param array $models
     * @return
     */
    public function newCollection(array $models = [])
    {
        foreach ($models as $k => $model) {
            if ($collectionKey = $model->collectionKey()) {
                $models[$k] = $this->convertToCollectionInstance($model, $collectionKey);
            }
        }

        return parent::newCollection($models);
    }

    /**
     * Clone the model into its expected collection class
     * @ref \Illuminate\Database\Eloquent\Model::replicate()
     *
     * @param Model $model
     * @param string $collectionKey
     * @return Model
     */
    private function convertToCollectionInstance(Model $model, string $collectionKey): Model
    {
        // Here we load up the proper collection model instead of the generic base class.
        return tap(static::fromCollectionKey($collectionKey), function ($instance) use ($model) {

            $instance->setRawAttributes($model->attributes);
            $instance->setRelations($model->relations);
            $instance->exists = $model->exists;

            $this->loadCustomTranslations($instance);
        });
    }

    /**
     * When eager loading the translations via the with attribute, they are loaded every time.
     * Here we eager load the proper translations if they are set on a different model than the original one.
     * The current loaded translations are empty because of they tried matching with the original table.
     *
     * @param $instance
     * @param $this
     */
    private function loadCustomTranslations($instance)
    {
        if ($this->requiresCustomTranslation($instance))
        {
            if (!is_array($instance->with) || !in_array('translations', $instance->with))
            {
                $instance->unsetRelation('translations');
            } else
            {
                $instance->load('translations');
            }
        }
    }

    /**
     * @param $instance
     * @return bool
     */
    private function requiresCustomTranslation(Model $instance): bool
    {
        return $instance->relationLoaded('translations') && $instance->translations->isEmpty() && $instance->translationModel != PageTranslation::class;
    }

    /**
     * Create a new instance of the given model.
     *
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        if (!isset($attributes['collection'])) {
            return parent::newInstance($attributes, $exists);
        }

        $class = static::fromCollectionKey($attributes['collection']);

        // This method just provides a convenient way for us to generate fresh model
        // instances of this current model. It is particularly useful during the
        // hydration of new objects via the Eloquent query builder instances.
        $model = new $class((array) $attributes);

        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

        return $model;
    }

    public function collectionKey(): string
    {
        // Collection key is stored at db - if not we map it from our config
        if ($this->collection) {
            return $this->collection;
        }

        $mapping = static::mapping();

        if (false == ($key = array_search(static::class, $mapping))) {
            throw new NotFoundCollectionKey('Collection key expected but none found for ' . static::class.'. Please provide a collection key in the chief config file and on your model as model::collection property.');
        }

        return $key;
    }

    public static function fromCollectionKey(string $key = null, $attributes = [])
    {
        $mapping = static::mapping();

        if (!isset($mapping[$key])) {
            throw new NotFoundCollectionKey('No corresponding class found for the collection key ['.$key.']. Make sure to add this to the [thinktomorrow.chief.collections] config array.');
        }

        return new $mapping[$key]($attributes);
    }

    public function scopeCollection($query, string $collection = null)
    {
        return $query->withoutGlobalScope(static::globalCollectionScope())
                     ->where('collection', '=', $collection);
    }

    /**
     * Ignore the collection scope.
     * This will fetch all results, regardless of the collection scope.
     */
    public static function ignoreCollection()
    {
        return self::withoutGlobalScope(static::globalCollectionScope());
    }

    public static function availableCollections($refresh = false): Collection
    {
        if ($refresh) {
            static::$availableCollections = null;
        }

        if (static::$availableCollections) {
            return static::$availableCollections;
        }

        $availableCollections = collect(static::mapping())->map(function ($className) {
            return (new $className)->collectionDetails();
        });

        return static::$availableCollections = $availableCollections;
    }

    private static function mapping()
    {
        $mappings = [
            'pages' => Page::class,
            'modules' => Module::class,
        ];

        $type = 'pages';

        /**
         * Hacky way to determine the collections per type. This
         * is currently either 'pages' or 'modules'. If anything
         * else, we will resort to the default pages.
         */
        foreach($mappings as $_type => $class) {
            if(new static instanceof $class) {
                $type = $_type;
            }
        }

        return config('thinktomorrow.chief.collections.'.$type, []);
    }

    private static function globalCollectionScope()
    {
        $scopeClass = new GlobalCollectionScope();

        // A modal can have a custom scope class reference. This is set with a collectionScopeClass property.
        if (isset(static::$collectionScopeClass)) {
            $scopeClass = new static::$collectionScopeClass();
        }

        return $scopeClass;
    }
}
