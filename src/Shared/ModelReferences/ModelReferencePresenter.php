<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\ModelReferences;

use Illuminate\Support\Collection;

class ModelReferencePresenter
{
    public static function toSelectValues(Collection $collection): Collection
    {
        return $collection->map(function (ReferableModel $item) {
            return [
                'id'    => $item->modelReference()->get(),
                'label' => $item->modelReferenceLabel(),
                'group' => $item->modelReferenceGroup(),
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
