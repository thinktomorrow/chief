<?php

namespace Thinktomorrow\Chief\Table\Tests\Fixtures;

use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Filters\SearchFilter;
use Thinktomorrow\Chief\Table\Sorters\Sort;
use Thinktomorrow\Chief\Table\Sorters\TreeSort;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Table\References\TableReference;

class FilteredTreeBreadcrumbTableFixture
{
    public static function make(): Table
    {
        return Table::make()
            ->setTableReference(new TableReference(static::class, 'make'))
            ->query(fn () => TreeModelFixture::query())
            ->returnResultsAsTree()
            ->setTreeResource(new TreeResourceFixture)
            ->columns([
                ColumnText::make('title'),
            ])
            ->filters([
                SearchFilter::make('title'),
            ])
            ->sorters([
                Sort::make('title_asc')->query(fn ($builder) => $builder->orderBy('title')),
                TreeSort::default(),
            ]);
    }
}
