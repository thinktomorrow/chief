<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Support\Collection;

trait HasCollection
{
    private static $availableCollections;

    protected static function bootHasCollection()
    {
        static::addGlobalScope(new PageCollectionScope());
    }

    public static function collectionKey()
    {
        $mapping = config('thinktomorrow.chief.collections',[]);

        return false != ($key = array_search(static::class, $mapping)) ? $key : null;
    }

    public static function fromCollectionKey(string $key = null)
    {
        $mapping = config('thinktomorrow.chief.collections',[]);

        if(!isset($mapping[$key])){
            throw new \DomainException('No corresponding class found for the collection key ['.$key.']. Make sure to add this to the [thinktomorrow.chief.collections] config array.');
        }

        return new $mapping[$key];
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
        $collectionKey = static::collectionKey();

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
        return $query->withoutGlobalScope(PageCollectionScope::class)
                     ->where('collection', '=', $collection);
    }

    /**
     * Ignore the collection scope.
     * This will fetch all page results, regardless of the collection scope.
     */
    public static function ignoreCollection()
    {
        return self::withoutGlobalScope(PageCollectionScope::class);
    }

    public static function availableCollections(): Collection
    {
        if(static::$availableCollections) return static::$availableCollections;

        $mapping = config('thinktomorrow.chief.collections',[]);

        $availableCollections = collect($mapping)->map(function($className){
            return $className::collectionDetails();
        });

        return static::$availableCollections = $availableCollections;
    }

    public static function freshAvailableCollections()
    {
        self::$availableCollections = null;

        return static::availableCollections();
    }
}