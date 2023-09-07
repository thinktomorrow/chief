<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Thinktomorrow\Chief\ManagedModels\Filters\Presets\InputFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\RadioFilter;
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
        return RadioFilter::make('online', function ($query, $value) use ($key) {
            return $query->where($key, '=', $value);
        })->options([
            '' => 'Alle',
            SimpleState::online->getValueAsString() => 'online',
            SimpleState::offline->getValueAsString() => 'offline',
        ]);
    }

    public static function column(string $name, string|array $columns, ?string $label = null): Filter
    {
        return InputFilter::make($name, function ($query, $value) use ($columns) {
            return $query->where(function ($builder) use ($value, $columns) {
                foreach ($columns as $column) {
                    $builder->orWhere($column, 'LIKE', '%' . $value . '%');
                }

                return $builder;
            });
        })->label($label ?? $name);
    }

    public static function text(string $queryParameter, array $dynamicColumns = ['title'], array $astrotomicColumns = [], string $label = 'Titel', string $jsonColumn = 'values'): Filter
    {
        return InputFilter::make($queryParameter, function ($query, $value) use ($dynamicColumns, $astrotomicColumns, $jsonColumn) {
            return $query->where(function ($builder) use ($value, $dynamicColumns, $astrotomicColumns, $jsonColumn) {
                foreach ($dynamicColumns as $column) {
                    $jsonColumnParts = explode('.', $jsonColumn);
                    $builder->orWhereRaw('LOWER(json_extract(`' . implode('`.`', $jsonColumnParts) . '`, "$.' . $column . '")) LIKE ?', '%' . trim(strtolower($value)) . '%');
                }

                foreach ($astrotomicColumns as $column) {
                    $builder->orWhereTranslationLike($column, '%' . $value . '%');
                }

                return $builder;
            });
        })->label($label);
    }
}
