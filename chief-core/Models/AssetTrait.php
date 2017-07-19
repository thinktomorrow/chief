<?php

namespace Chief\Models;

trait AssetTrait
{
    public function asset()
    {
        return $this->morphMany(Asset::class, 'model');
    }
}