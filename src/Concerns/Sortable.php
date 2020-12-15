<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Concerns;

trait Sortable
{
    protected $sortableAttribute = 'order';

    public function scopeSortedManually($query)
    {
        return $query->orderBy($this->sortableAttribute, 'ASC');
    }
}
