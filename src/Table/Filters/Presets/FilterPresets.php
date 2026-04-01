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
    public static function searchQuery(string|array $columns = [], string|array $dynamicKeys = [], string $dynamicColumn = 'values', bool $strictSearch = false): callable
    {
        return function ($query, $value) use ($dynamicKeys, $columns, $dynamicColumn, $strictSearch) {
            $searchValues = static::extractSearchValues($value, $strictSearch);

            if (count($searchValues) < 1) {
                return $query;
            }

            return $query->where(function ($builder) use ($searchValues, $dynamicKeys, $columns, $dynamicColumn) {
                foreach ($searchValues as $searchValue) {
                    $builder->where(function ($nestedBuilder) use ($searchValue, $dynamicKeys, $columns, $dynamicColumn) {
                        $directColumns = [];

                        foreach ((array) $columns as $column) {
                            if (strpos($column, '.') !== false) {
                                [$relation, $columnName] = explode('.', $column);

                                $nestedBuilder->orWhereHas($relation, function ($query) use ($searchValue, $columnName, $dynamicColumn) {
                                    return static::queryColumnsOrDynamicAttributes($query->whereRaw('1=0'), $searchValue, [$columnName], [], $dynamicColumn);
                                });

                                continue;
                            }

                            $directColumns[] = $column;
                        }

                        return static::queryColumnsOrDynamicAttributes($nestedBuilder, $searchValue, $directColumns, $dynamicKeys, $dynamicColumn);
                    });
                }
            });
        };
    }

    /**
     * Search on a column, relation column or dynamic key.
     * Relation column can be searched by via relation.column
     */
    public static function input(string $name, string|array $columns = [], string|array $dynamicKeys = [], string $dynamicColumn = 'values', bool $strictSearch = false): Filter
    {
        return TextFilter::make($name)->query(static::searchQuery($columns, $dynamicKeys, $dynamicColumn, $strictSearch));
    }

    /**
     * Search on a column, relation column or dynamic key.
     * Relation column can be searched by via relation.column
     */
    public static function search(string $name, string|array $columns = [], string|array $dynamicKeys = [], string $dynamicColumn = 'values', bool $strictSearch = false): Filter
    {
        return SearchFilter::make($name)->query(static::searchQuery($columns, $dynamicKeys, $dynamicColumn, $strictSearch));
    }

    private static function extractSearchValues(string|array $value, bool $strictSearch): array
    {
        $values = is_array($value) ? $value : [trim($value)];
        $values = array_values(array_filter(array_map('trim', $values), fn (string $item) => $item !== ''));

        if ($strictSearch || count($values) < 1) {
            return $values;
        }

        $searchValues = [];

        foreach ($values as $item) {
            $searchValues = [
                ...$searchValues,
                ...preg_split('/\s+/', $item, flags: PREG_SPLIT_NO_EMPTY),
            ];
        }

        return $searchValues;
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
