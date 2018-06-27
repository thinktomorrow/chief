<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;

class CollectionItems extends Collection
{
    /**
     * Inflate collection id strings to their respective models
     *
     * @param \Traversable $collectionIds
     * @return Collection
     */
    public static function inflate(\Traversable $collectionIds): Collection
    {
        if (count($collectionIds) == 1 && is_null(reset($collectionIds))) {
            $collectionIds = [];
        }

        return (new static($collectionIds))->map(function (string $collectionId) {
            return CollectionId::fromString($collectionId)->instance();
        });
    }

    public static function availableChildren(ActsAsParent $parent): self
    {
        $available_children_types = config('thinktomorrow.chief.relations.children', []);
        $available_collections = array_merge(
            config('thinktomorrow.chief.collections.pages', []),
            config('thinktomorrow.chief.collections.modules', [])
        );

        $collection = new static();

        foreach ($available_children_types as $type) {
            $model = new $type();

            if ($collection_key = array_search($type, $available_collections)) {
                $model->collection = $collection_key;
            }

            $collection = $collection->merge($model->all());
        }

        return $collection;
    }
    
    public static function fromCollection(Collection $collection): self
    {
        // Assert each item honours our contract
        return new static($collection->map(function (HasCollectionId $item) {
            return $item;
        }));
    }

    public function details(): Collection
    {
        return $this->map(function (HasCollectionId $item) {
            return [
                'id'    => $item->getCollectionId(),
                'label' => $item->getCollectionLabel(),
                'group' => $item->getCollectionGroup(),
            ];
        });
    }

    public function groupedDetails(): Collection
    {
        $grouped = [];

        $this->details()->each(function ($item) use (&$grouped) {
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
