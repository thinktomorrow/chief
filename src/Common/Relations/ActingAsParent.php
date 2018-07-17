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
        $grouped_children = [];
        $children = $this->children();

        foreach($children as $i => $child) {

            $key = $i;

            if($child instanceof Page) {

                // Pages are presented in one module file with the collection of all pages combined
                // Only if they are sorted right after each other
                if(!isset($grouped_children[$key])) {
                    $grouped_children[$key] = new CollectedPages();
                }

                $grouped_children[$key]->push($child);
                continue;
            }

            $grouped_children[$key] = $child;
        }

        return collect($grouped_children)->map(function (PresentForParent $child) {
            return $child->presentForParent($this);
        });
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
