<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\ModelReferences;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;

class ModelReferencePresenter
{
    public static function toSelectValues(Collection $collection): Collection
    {
        /** @var Registry $registry */
        $registry = app(Registry::class);

        return $collection->map(function (ReferableModel $item) use ($registry) {

            /** @var PageResource $resource */
            $resource = $registry->findResourceByModel($item::class);

            return [
                'id' => $item->modelReference()->getShort(),
                'label' => $resource->getPageTitleForSelect($item),
                'group' => ucfirst($resource->getLabel()),
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
