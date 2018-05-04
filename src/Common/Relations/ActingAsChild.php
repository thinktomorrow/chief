<?php

namespace Chief\Common\Relations;

trait ActingAsChild
{
    public function acceptsParent(ActsAsParent $parent)
    {
        $this->parents()->attach($parent, ['parent_type' => get_class($parent)]);
    }

    public function parents()
    {
        return $this->morphToMany(get_class($this), 'child', 'relations', 'child_id', 'parent_id');
    }
}