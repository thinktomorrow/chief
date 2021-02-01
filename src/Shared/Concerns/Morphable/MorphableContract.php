<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Morphable;

interface MorphableContract
{
    /**
     * Unique identifier of the collection this item belongs to. This refers to the class name or to
     * the morph key as set by the \Illuminate\Database\Eloquent\Relations\Relation::morphMap().
     *
     * @return string|null
     */
    public function morphKey(): ?string;

    /**
     * Eloquent scope method to specify a query to only match results
     * for a given morphable class
     *
     * @param $query
     * @param string|null $morphkey
     * @return mixed
     */
    public function scopeMorphable($query, string $morphkey = null);

    /**
     * Ignore the global morphable scope and fetch all results,
     * regardless of the current global collection scope.
     */
    public static function ignoreMorphables();
}
