<?php

namespace Thinktomorrow\Chief\TableNew\Sorters;

class TreeSort extends Sorter
{
    const TREE_SORTING = 'tree-sorting';

    protected string $view = 'chief-table-new::sorters.sort';

    public static function make(string $key): static
    {
        $sort = new static($key);

        $sort->query(function ($query) {
        });

        return $sort;
    }

    public static function default(): static
    {
        return static::make(static::TREE_SORTING)
            ->actAsDefault()
            ->hideActiveLabel()
            ->label('Boomstructuur');
    }
}
