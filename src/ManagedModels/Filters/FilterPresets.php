<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Thinktomorrow\Chief\ManagedModels\Filters\Presets\InputFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\RadioFilter;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;

class FilterPresets
{
    public static function state(): Filter
    {
        return RadioFilter::make('online', function ($query, $value) {
            return $query->where('current_state', '=', $value);
        })->options([
            '' => 'Alle',
            PageState::published->getValueAsString() => 'online',
            PageState::draft->getValueAsString() => 'offline',
        ]);
    }

    public static function text(string $queryParameter, array $dynamicColumns = ['title'], array $astrotomicColumns = [], string $label = 'titel', string $jsonColumn = 'values'): Filter
    {
        return InputFilter::make($queryParameter, function ($query, $value) use ($dynamicColumns, $astrotomicColumns, $jsonColumn) {
            return $query->where(function ($builder) use ($value, $dynamicColumns, $astrotomicColumns, $jsonColumn) {
                foreach ($dynamicColumns as $column) {
                    $builder->orWhereRaw('LOWER(json_extract(`'.$jsonColumn.'`, "$.'.$column.'")) LIKE ?', '%'. trim(strtolower($value)) . '%');
                }

                foreach ($astrotomicColumns as $column) {
                    $builder->orWhereTranslationLike($column, '%'.$value.'%');
                }

                return $builder;
            });
        })->label($label);
    }
}
