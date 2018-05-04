<?php

namespace Chief\Common\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ActingAsParent
{
    public function adoptChild(ActsAsChild $child)
    {
        $this->children()->attach($child, ['child_type' => get_class($child)]);
    }

    public function children()
    {
        // Builder $query, Model $parent, $table, $foreignPivotKey,
        // $relatedPivotKey, $parentKey, $relatedKey, $relationName = null

//        return (new BelongsToMany($this->newQuery(), $this, 'relations', 'child_id', 'parent_id', 'id', 'id'))->where('parent_type',get_class($this));
//
//        return $this->morphedByMany(get_class($this), 'parent', 'relations', 'child_id', 'parent_id');
//        return $this->morphedByMany(ActingAsChild::class, 'child', 'relations', 'child_id', 'parent_id');
        return $this->morphedByMany(ActingAsChild::class, 'child', 'relations', 'child_id', 'parent_id');
    }
}