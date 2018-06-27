<?php

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait HasCollection
{
    private static $availableCollections;

    protected static function bootHasCollection()
    {
        if (!isset(static::$collectionScopeClass)) {
            throw new \DomainException('Class with HasCollection trait require a static property [collectionScopeClass]. This should refer to a global scope class.');
        }

        static::addGlobalScope(new static::$collectionScopeClass());
    }

    /**
     * Clone the model into its expected collection class
     * @ref \Illuminate\Database\Eloquent\Model::replicate()
     *
     * @param Model $model
     * @param string $collectionKey
     * @return Model
     */
    public function convertToCollectionInstance(Model $model, string $collectionKey): Model
    {
        // Here we load up the proper collection model instead of the generic base class.
        return tap(static::fromCollectionKey($collectionKey), function ($instance) use ($model) {
            $instance->setRawAttributes($model->attributes);
            $instance->setRelations($model->relations);
            $instance->exists = $model->exists;
        });
    }

    /**
     * Custom build for new Collections where we convert any models to the correct collection types.
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

    public function collectionKey()
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

    /**
     * Details of the collection such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     *
     * @param null $key
     * @return object
     */
    public static function collectionDetails($key = null)
    {
        $collectionKey = (new static)->collectionKey();

        $names = (object) [
            'key'      => $collectionKey,
            'class'    => static::class,
            'singular' => ucfirst(str_singular($collectionKey)),
            'plural'   => ucfirst(str_plural($collectionKey)),
        ];

        return $key ? $names->$key : $names;
    }

    public function scopeCollection($query, string $collection = null)
    {
        return $query->withoutGlobalScope(static::$collectionScopeClass)
                     ->where('collection', '=', $collection);
    }

    /**
     * Ignore the collection scope.
     * This will fetch all results, regardless of the collection scope.
     */
    public static function ignoreCollection()
    {
        return self::withoutGlobalScope(static::$collectionScopeClass);
    }

    public static function availableCollections(): Collection
    {
        if (static::$availableCollections) {
            return static::$availableCollections;
        }

        $availableCollections = collect(static::mapping())->map(function ($className) {
            return $className::collectionDetails();
        });

        return static::$availableCollections = $availableCollections;
    }

    public static function freshAvailableCollections()
    {
        self::$availableCollections = null;

        return static::availableCollections();
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
}
