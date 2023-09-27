<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\ModelReferences;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;

class ModelReferencePresenter
{
    public static function toGroupedSelectValues(Collection $collection): Collection
    {
        $grouped = [];

        static::toSelectValues($collection, true)->each(function ($item) use (&$grouped) {
            if (isset($grouped[$item['group']])) {
                $grouped[$item['group']]['options'][] = $item;
            } else {
                $grouped[$item['group']] = ['label' => $item['group'], 'options' => [$item]];
            }
        });

        // We remove the group key as we need to have non-assoc array for the multiselect options.
        return collect(array_values($grouped));
    }

    /**
     * Select values prepared for the multiselect options
     *
     * @param Collection $collection
     * @return Collection
     */
    public static function toSelectValues(Collection $collection, bool $prepareForGrouping = false): Collection
    {
        /** @var Registry $registry */
        $registry = app(Registry::class);

        return $collection->map(function (ReferableModel $item) use ($registry, $prepareForGrouping) {
            /** @var PageResource $resource */
            $resource = $registry->findResourceByModel($item::class);

            return [
                'value' => $item->modelReference()->getShort(),
                'label' => $resource->getPageTitleForSelect($item),
                ...($prepareForGrouping ? ['group' => ucfirst($resource->getLabel())] : []),
            ];
        });
    }
}
