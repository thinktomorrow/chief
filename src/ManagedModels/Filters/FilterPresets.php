<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Thinktomorrow\Chief\ManagedModels\Filters\Presets\InputFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\RadioFilter;
use Thinktomorrow\Chief\ManagedModels\States\PageState;

class FilterPresets
{
    public static function state(): Filter
    {
        return RadioFilter::make('online', function ($query, $value) {
            return $query->where('current_state', '=', $value);
        })->options([
            '' => 'Alle',
            PageState::PUBLISHED => 'online',
            PageState::DRAFT => 'offline',
        ]);
    }

    public static function text(array $dynamicColumns = ['title'], array $astrotomicColumns = [], string $label = 'titel'): Filter
    {
        return InputFilter::make('search', function ($query, $value) use ($dynamicColumns, $astrotomicColumns) {
            return $query->where(function ($builder) use ($value, $dynamicColumns, $astrotomicColumns) {
                foreach ($dynamicColumns as $column) {
                    $builder->orWhereRaw('LOWER(json_extract(`values`, "$.'.$column.'")) LIKE ?', '%'. trim(strtolower($value)) . '%');
                }

                foreach ($astrotomicColumns as $column) {
                    $builder->orWhereTranslationLike($column, '%'.$value.'%');
                }

                return $builder;
            });
        })->label($label);
    }
}
