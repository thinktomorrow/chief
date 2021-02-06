<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns;

trait Sortable
{
    /**
     * Is this model sortable in the chief admin? If so, the index page will allow
     * the user to sort the models manually.
     * @return bool
     */
    public function isSortable(): bool
    {
        return true;
    }

    /**
     * The column / attribute name that represents the order value.
     */
    public function sortableAttribute(): string
    {
        return 'order';
    }

    public function scopeSortedManually($query)
    {
        return $query->orderBy($this->sortableAttribute(), 'ASC');
    }
}
