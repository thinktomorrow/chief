<?php

namespace Chief\Common\Relations;

trait ActingAsChild
{
    protected $loadedParentRelations;

    public function acceptParent(ActsAsParent $parent)
    {
        // Reset cached relation
        $this->loadedParentRelations = null;

        $this->attachParent($parent->getMorphClass(), $parent->getKey());
    }

    public function parents()
    {
        if($this->areParentRelationsLoaded()){
            return $this->loadedParentRelations;
        }

        return $this->loadedParentRelations = Relation::parents($this->getMorphClass(), $this->getKey());
    }

    private function attachParent($parent_type, $parent_id)
    {
        Relation::firstOrCreate([
            'parent_type'  => $parent_type,
            'parent_id'    => $parent_id,
            'child_type' => $this->getMorphClass(),
            'child_id'   => $this->getKey(),
        ]);
    }

    private function areParentRelationsLoaded(): bool
    {
        return !is_null($this->loadedParentRelations);
    }
}