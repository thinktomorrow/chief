<?php

namespace Thinktomorrow\Chief\Common\Relations;

use Illuminate\Database\Eloquent\Collection;
use Thinktomorrow\Chief\Pages\CollectedPages;
use Thinktomorrow\Chief\Pages\Page;

trait ActingAsParent
{
    protected $loadedChildRelations;

    public function children(): Collection
    {
        if ($this->areChildRelationsLoaded()) {
            return $this->loadedChildRelations;
        }
        return $this->loadedChildRelations = $this->freshChildren();
    }

    public function freshChildren(): Collection
    {
        $this->loadedChildRelations = null;

        return new Collection(Relation::children($this->getMorphClass(), $this->getKey())->all());
    }

    public function adoptChild(ActsAsChild $child, array $attributes = [])
    {
        // Reset cached relation
        $this->loadedChildRelations = null;

        $this->attachChild($child->getMorphClass(), $child->getKey(), $attributes);
    }

    public function rejectChild(ActsAsChild $child)
    {
        // Reset cached relation
        $this->loadedChildRelations = null;

        $this->detachChild($child->getMorphClass(), $child->getKey());
    }

    public function renderChildren(): string
    {
        return $this->presentChildren()->implode('');
    }

    public function presentChildren(): \Illuminate\Support\Collection
    {
        $grouped_children = $this->combinePagesIntoCollections($this->children());

        return collect($grouped_children)->map(function (PresentForParent $child) {
            return $child->presentForParent($this);
        });
    }

    /**
     * Pages are presented in one module file with the collection of all pages combined
     * But only if they are sorted right after each other
     *
     * @param $children
     * @return array
     */
    private function combinePagesIntoCollections($children): array
    {
        $grouped_children = [];
        $collected_pages_key = null;
        $collected_pages_type = null;

        foreach ($children as $i => $child)
        {
            $key = $i;

            if ($child instanceof Page)
            {

                // Only published pages you fool!
                if (!$child->isPublished())
                {
                    continue;
                }

                // Set the current pages collection to the current collection type
                if ($collected_pages_type == null || $collected_pages_type != $child->collectionKey())
                {
                    $collected_pages_type = $child->collectionKey();
                    $collected_pages_key = $key;
                }

                if (!isset($grouped_children[$collected_pages_key]))
                {
                    $grouped_children[$collected_pages_key] = new CollectedPages();
                }

                $grouped_children[$collected_pages_key]->push($child);

                continue;
            }

            // Reset the grouped_collection if other than page type
            $collected_pages_key = null;

            $grouped_children[$key] = $child;
        }

        return $grouped_children;
    }

    public function relationWithChild(ActsAsChild $child): Relation
    {
        return Relation::query()
            ->where('parent_type', $this->getMorphClass())
            ->where('parent_id', $this->getKey())
            ->where('child_type', $child->getMorphClass())
            ->where('child_id', $child->getKey())
            ->first();
    }

    public function sortChild(ActsAsChild $child, $sort = 0)
    {
        $this->loadedChildRelations = null;

        Relation::query()
            ->where('parent_type', $this->getMorphClass())
            ->where('parent_id', $this->getKey())
            ->where('child_type', $child->getMorphClass())
            ->where('child_id', $child->getKey())
            ->update(['sort' => $sort]);
    }

    private function attachChild($child_type, $child_id, array $attributes = [])
    {
        Relation::firstOrCreate([
            'parent_type' => $this->getMorphClass(),
            'parent_id'   => $this->getKey(),
            'child_type'  => $child_type,
            'child_id'    => $child_id,
        ], $attributes);
    }

    private function detachChild($child_type, $child_id)
    {
        Relation::query()
            ->where('parent_type', $this->getMorphClass())
            ->where('parent_id', $this->getKey())
            ->where('child_type', $child_type)
            ->where('child_id', $child_id)
            ->delete();
    }

    private function areChildRelationsLoaded(): bool
    {
        return !is_null($this->loadedChildRelations);
    }
}
