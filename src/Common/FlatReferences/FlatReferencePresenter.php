<?php

namespace Thinktomorrow\Chief\Common\FlatReferences;

use Illuminate\Support\Collection;

class FlatReferencePresenter
{
    public static function toSelectValues(Collection $collection): Collection
    {
        return $collection->map(function (ProvidesFlatReference $item) {
            return [
                'id'    => $item->flatReference()->get(),
                'label' => $item->flatReferenceLabel(),
                'group' => $item->flatReferenceGroup(),
            ];
        });
    }

    public static function toGroupedSelectValues(Collection $collection): Collection
    {
        $grouped = [];

        static::toSelectValues($collection)->each(function ($item) use (&$grouped) {
            if (isset($grouped[$item['group']])) {
                $grouped[$item['group']]['values'][] = $item;
            } else {
                $grouped[$item['group']] = ['group' => $item['group'], 'values' => [$item]];
            }
        });

        // We remove the group key as we need to have non-assoc array for the multiselect options.
        return collect(array_values($grouped));
    }
}
