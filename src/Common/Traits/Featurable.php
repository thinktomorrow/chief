<?php

namespace Thinktomorrow\Chief\Common\Traits;

trait Featurable
{
    public function isFeatured()
    {
        return ($this->featured);
    }

    public function scopeFeatured($query)
    {
        $query->where('featured',1);
    }

    public function feature()
    {
        $this->featured = 1;
        $this->save();
    }

    public function unfeature()
    {
        $this->featured = 0;
        $this->save();
    }
}