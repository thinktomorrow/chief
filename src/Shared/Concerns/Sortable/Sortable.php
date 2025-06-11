<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Sortable;

interface Sortable
{
    /**
     * Is this model sortable in the chief admin? If so, the
     * index page will allow the user to sort the models manually.
     */
    public function isSortable(): bool;

    /**
     * The column / attribute name that represents the order value.
     */
    public function sortableAttribute(): string;

    /**
     * Scope to order the models by the sortable attribute.
     */
    public function scopeSortedManually($query): void;
}
