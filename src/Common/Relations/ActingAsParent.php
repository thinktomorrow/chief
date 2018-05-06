<?php

namespace Chief\Common\Relations;

use Illuminate\Database\Eloquent\Collection;

trait ActingAsParent
{
    protected $loadedChildRelations;

    public function children(): Collection
    {
        if($this->areChildRelationsLoaded()){
            return $this->loadedChildRelations;
        }
        return $this->loadedChildRelations = Relation::children($this->getMorphClass(), $this->getKey());
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

    public function presentChildren(): \Illuminate\Support\Collection
    {
        return $this->children()->map(function($child){
            return $child->presentForParent($this, $child->relation);
        });
    }

    public function relationWithChild(ActsAsChild $child): Relation
    {
        return Relation::first([
            'child_type'  => $child->getMorphClass(),
            'child_id'    => $child->getKey(),
            'parent_type' => $this->getMorphClass(),
            'parent_id'   => $this->getKey(),
        ]);
    }

    private function attachChild($child_type, $child_id, array $attributes = [])
    {
        // TODO: update sort when relation is found is not triggered...
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