<?php

namespace Chief\Common\Relations;

trait ActingAsParent
{
    protected $loadedChildRelations;

    public function adoptChild(ActsAsChild $child)
    {
        // Reset cached relation
        $this->loadedChildRelations = null;

        $this->attachChild($child->getMorphClass(), $child->getKey());
    }

    public function children()
    {
        if($this->areChildRelationsLoaded()){
            return $this->loadedChildRelations;
        }
        return $this->loadedChildRelations = Relation::children($this->getMorphClass(), $this->getKey());
    }

    private function attachChild($child_type, $child_id)
    {
        Relation::firstOrCreate([
            'parent_type' => $this->getMorphClass(),
            'parent_id'   => $this->getKey(),
            'child_type'  => $child_type,
            'child_id'    => $child_id,
        ]);
    }

    private function areChildRelationsLoaded(): bool
    {
        return !is_null($this->loadedChildRelations);
    }
}