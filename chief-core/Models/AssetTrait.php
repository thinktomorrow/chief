<?php

namespace Chief\Models;

trait AssetTrait
{
    public function asset()
    {
        return $this->morphMany(Asset::class, 'model');
    }

    public function __call($method, $args)
    {
        if(in_array($method, Asset::$conversions) === true)
        {
            if(is_array($args[$method]) && count($args) > 0)
            {
                if(true === array_key_exists($args[0], $args[$method]))
                {
                    return new self($args[$method][$args[0]]);
                }
            }
            return $args[$method];
        }
    }

}