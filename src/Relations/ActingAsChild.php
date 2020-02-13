<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Relations;

use Illuminate\Database\Eloquent\Collection;

trait ActingAsChild
{
    protected $loadedParentRelations;

    public function parents(): Collection
    {
        if ($this->areParentRelationsLoaded()) {
            return $this->loadedParentRelations;
        }

        return $this->loadedParentRelations = Relation::parents($this->getMorphClass(), $this->getKey());
    }

    public function acceptParent(ActsAsParent $parent, array $attributes = [])
    {
        // Reset cached relation
        $this->loadedParentRelations = null;

        $this->attachParent($parent->getMorphClass(), $parent->getKey(), $attributes);
    }

    public function rejectParent(ActsAsParent $parent)
    {
        // Reset cached relation
        $this->loadedParentRelations = null;

        $this->detachParent($parent->getMorphClass(), $parent->getKey());
    }

    public function relationWithParent(ActsAsParent $parent): Relation
    {
        return Relation::query()
            ->where('parent_type', $parent->getMorphClass())
            ->where('parent_id', $parent->getKey())
            ->where('child_type', $this->getMorphClass())
            ->where('child_id', $this->getKey())
            ->first();
    }

    public function detachAllParentRelations()
    {
        Relation::deleteAllParentRelationsOf($this->getMorphClass(), $this->getKey());
    }

    private function attachParent($parent_type, $parent_id, array $attributes = [])
    {
        Relation::firstOrCreate([
            'parent_type' => $parent_type,
            'parent_id'   => $parent_id,
            'child_type'  => $this->getMorphClass(),
            'child_id'    => $this->getKey(),
        ], $attributes);
    }

    private function detachParent($parent_type, $parent_id)
    {
        Relation::query()
            ->where('child_type', $this->getMorphClass())
            ->where('child_id', $this->getKey())
            ->where('parent_type', $parent_type)
            ->where('parent_id', $parent_id)
            ->delete();
    }

    private function areParentRelationsLoaded(): bool
    {
        return !is_null($this->loadedParentRelations);
    }
}
