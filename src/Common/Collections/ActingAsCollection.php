<?php

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\FlatReferences\ActsAsFlatReference;
use Thinktomorrow\Chief\Common\FlatReferences\Types\CollectionFlatReference;

trait ActingAsCollection
{
    private static $availableCollections;

    protected static function bootActingAsCollection()
    {
        static::addGlobalScope(static::globalCollectionScope());
    }

    public function flatReference(): ActsAsFlatReference
    {
        return new CollectionFlatReference(static::class, $this->id);
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

        return $key ? $names->$key : $names;
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
        });
    }

    public function collectionKey(): ?string
    {
        // Collection key is stored at db - if not we map it from our config
        if ($this->collection) {
            return $this->collection;
        }

        $mapping = static::mapping();

        return false != ($key = array_search(static::class, $mapping)) ? $key : null;
    }

    public static function fromCollectionKey(string $key = null, $attributes = [])
    {
        $mapping = static::mapping();

        if (!isset($mapping[$key])) {
            throw new \DomainException('No corresponding class found for the collection key ['.$key.']. Make sure to add this to the [thinktomorrow.chief.collections] config array.');
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
        if($refresh) {
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
        /**
         * Hacky way to determine the collections per type. This
         * is currently either 'pages' or 'modules'.
         */
        $collection = (new static)->getTable();

        return config('thinktomorrow.chief.collections.'.$collection, []);
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
