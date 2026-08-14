<?php

namespace Thinktomorrow\Chief\Table\Tests\Fixtures;

use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Filters\SelectFilter;
use Thinktomorrow\Chief\Table\Sorters\Sort;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Table\References\TableReference;

class ScopedTableStateFixture
{
    public static function scopedFilters(): Table
    {
        return static::base('scopedFilters')
            ->filters([
                SelectFilter::make('period')
                    ->options(['current' => 'Current', 'archived' => 'Archived'])
                    ->value('current')
                    ->scoped(),
                SelectFilter::make('title')
                    ->options(['child1 title' => 'Child 1', 'child2 title' => 'Child 2']),
            ]);
    }

    public static function limitedScopedFilters(): Table
    {
        return static::base('limitedScopedFilters')
            ->filters([
                SelectFilter::make('period')
                    ->options(['current' => 'Current', 'archived' => 'Archived'])
                    ->value('current')
                    ->scoped(['title']),
                SelectFilter::make('title')
                    ->options(['child1 title' => 'Child 1', 'child2 title' => 'Child 2']),
                SelectFilter::make('status')
                    ->options(['open' => 'Open']),
            ]);
    }

    public static function optionsFromActiveFilters(): Table
    {
        return static::base('optionsFromActiveFilters')
            ->filters([
                SelectFilter::make('period')
                    ->options(['current' => 'Current', 'archived' => 'Archived'])
                    ->value('current'),
                SelectFilter::make('title')
                    ->options(fn ($filter, $locale, array $filters): array => ($filters['period'] ?? 'current') === 'archived'
                        ? ['archived title' => 'Archived title']
                        : ['current title' => 'Current title']),
            ]);
    }

    public static function scopedSorters(): Table
    {
        return static::base('scopedSorters')
            ->filters([
                SelectFilter::make('period')
                    ->options(['current' => 'Current', 'archived' => 'Archived'])
                    ->value('current')
                    ->scoped(['title_desc']),
            ])
            ->sorters([
                Sort::make('title_desc')->query(fn ($builder) => $builder->orderBy('title', 'desc')),
            ]);
    }

    private static function base(string $method): Table
    {
        return Table::make()
            ->setTableReference(new TableReference(static::class, $method))
            ->query(fn () => TreeModelFixture::query())
            ->columns([
                ColumnText::make('id'),
                ColumnText::make('title'),
            ]);
    }
}
