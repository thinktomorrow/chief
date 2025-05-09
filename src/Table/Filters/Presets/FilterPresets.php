<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters\Presets;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\SimpleState\SimpleState;
use Thinktomorrow\Chief\Table\Filters\Filter;
use Thinktomorrow\Chief\Table\Filters\SearchFilter;
use Thinktomorrow\Chief\Table\Filters\SelectFilter;
use Thinktomorrow\Chief\Table\Filters\TextFilter;

class FilterPresets
{
    public static function state(string $key = 'current_state'): Filter
    {
        return SelectFilter::make('online')->query(function ($query, $value) use ($key) {
            return $query->where($key, '=', $value);
        })->label('Status')->options([
            '' => 'Alle',
            PageState::published->getValueAsString() => 'Online',
            PageState::draft->getValueAsString() => 'Offline',
        ])->default('');
    }

    public static function simpleState(string $key = 'current_state'): Filter
    {
        return SelectFilter::make('online')->query(function ($query, $value) use ($key) {
            return $query->where($key, '=', $value);
        })->label('Status')->options([
            '' => 'Alle',
            SimpleState::online->getValueAsString() => 'online',
            SimpleState::offline->getValueAsString() => 'offline',
        ])->default('');
    }

    /**
     * Search on a column, relation column or dynamic key.
     * Relation column can be searched by via relation.column
     */
    public static function searchQuery(string|array $columns = [], string|array $dynamicKeys = [], string $dynamicColumn = 'values'): callable
    {
        return function ($query, $value) use ($dynamicKeys, $columns, $dynamicColumn) {
            return $query->where(function ($builder) use ($value, $dynamicKeys, $columns, $dynamicColumn) {

                // Extract relation searches
                foreach ($columns as $i => $column) {
                    if (strpos($column, '.') !== false) {
                        [$relation, $columnName] = explode('.', $column);

                        $builder->whereHas($relation, function ($query) use ($value, $columnName, $dynamicColumn) {
                            return static::queryColumnsOrDynamicAttributes($query->whereRaw('1=0'), $value, [$columnName], [], $dynamicColumn);
                        });

                        unset($columns[$i]);
                    }
                }

                // Columns or dynamic keys
                return static::queryColumnsOrDynamicAttributes($builder, $value, $columns, $dynamicKeys, $dynamicColumn);
            });
        };
    }

    /**
     * Search on a column, relation column or dynamic key.
     * Relation column can be searched by via relation.column
     */
    public static function input(string $name, string|array $columns = [], string|array $dynamicKeys = [], string $dynamicColumn = 'values'): Filter
    {
        return TextFilter::make($name)->query(static::searchQuery($columns, $dynamicKeys, $dynamicColumn));
    }

    /**
     * Search on a column, relation column or dynamic key.
     * Relation column can be searched by via relation.column
     */
    public static function search(string $name, string|array $columns = [], string|array $dynamicKeys = [], string $dynamicColumn = 'values'): Filter
    {
        return SearchFilter::make($name)->query(static::searchQuery($columns, $dynamicKeys, $dynamicColumn));
    }

    private static function queryColumnsOrDynamicAttributes(Builder $builder, $value, $columns, $dynamicKeys, $dynamicColumn): Builder
    {
        foreach ($columns as $column) {
            $builder->orWhere($column, 'LIKE', '%'.$value.'%');
        }

        foreach ($dynamicKeys as $dynamicKey) {
            $dynamicColumnParts = explode('.', $dynamicColumn);
            $builder->orWhereRaw('LOWER(json_extract(`'.implode('`.`', $dynamicColumnParts).'`, "$.'.$dynamicKey.'")) LIKE ?', '%'.trim(strtolower($value)).'%');
        }

        return $builder;
    }
}
