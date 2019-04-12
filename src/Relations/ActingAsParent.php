<?php

namespace Thinktomorrow\Chief\Relations;

use Illuminate\Database\Eloquent\Collection;
use Thinktomorrow\Chief\PageBuilder\PresentSections;

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

    // custom renderChildren method that can add classes based on theme if present
    public function renderChildrenWithThemeArray(array $themeArray): string
    {
        $output = "";
        if(!$themeArray) return $this->renderChildren();

        foreach ($this->presentChildren() as $i => $child) {
            $pos = strpos($child, 'class="') + 7;
            $className = $themeArray[$i%count($themeArray)];
            $newChild = substr_replace($child, $className.' ', $pos, 0);
            $output = $output.$newChild;
        } 
        return $output;
    }

    public function presentChildren(): \Illuminate\Support\Collection
    {
        return (new PresentSections())($this, $this->children());
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
