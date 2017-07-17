<?php

namespace Chief\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Article extends Model
{

    public function asset()
    {
        return $this->morphMany(Asset::class, 'model');
    }

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 150, 'h' => 150, 'sharp'=> 15]);

        $this->addMediaConversion('icon')
            ->setManipulations(['w' => 300, 'h' => 300, 'sharp' => 15]);
    }

}