<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\InputFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\SelectFilter;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\SimpleState\SimpleState;

class FilterPresets
{
    public static function state(string $key = 'current_state'): Filter
    {
        return SelectFilter::make('online', function ($query, $value) use ($key) {
            return $query->where($key, '=', $value);
        })->label('Status')->options([
            '' => 'Alle',
            PageState::published->getValueAsString() => 'online',
            PageState::draft->getValueAsString() => 'offline',
        ])->default('');
    }

    public static function simpleState(string $key = 'current_state'): Filter
    {
        return SelectFilter::make('online', function ($query, $value) use ($key) {
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
    public static function attribute(string $name, string|array $columns = [], string|array $dynamicKeys = [], string $label = 'Titel', string $dynamicColumn = 'values'): Filter
    {
        return InputFilter::make($name, function ($query, $value) use ($dynamicKeys, $columns, $dynamicColumn) {
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
        })->label($label);
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

    /**
     * @deprecated use attribute() search instead for an extensive usage range.
     */
    public static function column(string $name, string|array $columns, ?string $label = null): Filter
    {
        return InputFilter::make($name, function ($query, $value) use ($columns) {
            return $query->where(function ($builder) use ($value, $columns) {
                foreach ($columns as $column) {
                    $builder->orWhere($column, 'LIKE', '%'.$value.'%');
                }

                return $builder;
            });
        })->label($label ?? $name);
    }

    /**
     * @deprecated use attribute() search instead for an extensive usage range.
     */
    public static function text(string $queryParameter, array $dynamicColumns = ['title'], array $astrotomicColumns = [], string $label = 'Titel', string $jsonColumn = 'values'): Filter
    {
        return InputFilter::make($queryParameter, function ($query, $value) use ($dynamicColumns, $astrotomicColumns, $jsonColumn) {
            return $query->where(function ($builder) use ($value, $dynamicColumns, $astrotomicColumns, $jsonColumn) {
                foreach ($dynamicColumns as $column) {
                    $jsonColumnParts = explode('.', $jsonColumn);
                    $builder->orWhereRaw('LOWER(json_extract(`'.implode('`.`', $jsonColumnParts).'`, "$.'.$column.'")) LIKE ?', '%'.trim(strtolower($value)).'%');
                }

                foreach ($astrotomicColumns as $column) {
                    $builder->orWhereTranslationLike($column, '%'.$value.'%');
                }

                return $builder;
            });
        })->label($label);
    }
}
