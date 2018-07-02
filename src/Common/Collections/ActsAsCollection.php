<?php

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\FlatReferences\ProvidesFlatReference;

interface ActsAsCollection extends ProvidesFlatReference
{
    /**
     * Details of the collection such as naming, key and class.
     * Usable in several forms / screens of the application.
     *
     * @return CollectionDetails
     */
    public function collectionDetails(): CollectionDetails;

    /**
     * Unique identifier of the collection this item belongs to. This refers to
     * the key as set in the config chief > collections > pages / modules
     *
     * @return string
     */
    public function collectionKey(): string;

    /**
     * Make an instance based on the passed collection key. The class is
     * taken from the matching value in  the config chief > collection
     *
     * @param string|null $key
     * @param array $attributes
     * @return mixed
     */
    public static function fromCollectionKey(string $key = null, $attributes = []);

    /**
     * Eloquent scope method to specify a query to only match results
     * from a given collection.
     *
     * @param $query
     * @param string|null $collection
     * @return mixed
     */
    public function scopeCollection($query, string $collection = null);

    /**
     * Ignore the global collection scope and fetch all results,
     * regardless of the current global collection scope.
     */
    public static function ignoreCollection();

    /**
     * Listing of all available collections for this model type.
     * This will retrieve the values from the chief config.
     *
     * @param bool $refresh results are only fetched from source once but you can force a fresh retrieval if you like.
     * @return Collection
     */
    public static function availableCollections($refresh = false): Collection;
}