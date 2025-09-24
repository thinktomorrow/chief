<?php

namespace Thinktomorrow\Chief\Table\Sorters;

class ManualSort extends Sorter
{
    protected string $view = 'chief-table::sorters.sort';

    public static function make(string $key): static
    {
        $sort = new static($key);

        $sort->query(function ($query) {});

        return $sort;
    }

    public static function default(string $sortableAttribute = 'order'): static
    {
        return static::make('manual_order')
            ->label('Volgorde volgens site')
            ->query(fn ($query) => $query->orderBy($sortableAttribute, 'asc'))
            ->actAsDefault();
    }
}
