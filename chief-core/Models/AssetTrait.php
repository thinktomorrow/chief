<?php

namespace Chief\Models;

trait AssetTrait
{
    public function asset()
    {
        return $this->morphMany(Asset::class, 'model');
    }

    public function hasFile($collection = '')
    {
        return !! $this->getFileUrl($collection);
    }

    public function getFilename($collection = '')
    {
        if($this->asset->isEmpty())
        {
            return 'other.png';
        }else{
            return basename($this->getFileUrl($collection));
        }
    }

    public function getFileUrl($collection = '')
    {
        if($this->asset->isEmpty())
        {
            return '../assets/back/img/other.png';
        }else{
            $filename = '../assets/back/img/other.png';
            $this->asset->first()->getMedia()->each(function ($media) use($collection, &$filename){
                if($media->getCustomProperty('type') == $collection){
                    $filename =  $media->getUrl();
                }
            });
            return $filename;
        }
    }
}