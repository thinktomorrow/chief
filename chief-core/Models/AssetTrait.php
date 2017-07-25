<?php

namespace Chief\Models;

use Chief\Locale\Locale;

trait AssetTrait
{
    public function asset()
    {
        return $this->morphMany(Asset::class, 'model');
    }

    public function hasFile($type = '', $locale = '')
    {
        $filename = $this->getFilename($type, $locale);
        return !!$filename and basename($filename) != 'other.png' ;
    }

    public function getFilename($type = '', $locale = '')
    {
        return basename($this->getFileUrl($type, '', $locale));
    }

    public function getFileUrl($type = '', $size = '', $locale = '')
    {
        $assets = $this->asset->where('type', $type);
        if($assets->isEmpty()) return null;
        return $assets->first()->getFileUrl($size, $locale);
    }

    /**
     * Adds a file to this model, accepts a type and locale to be saved with the file
     *
     * @param $file
     * @param $type
     * @param string $locale
     */
    public function addFile($file, $type = '', $locale = '')
    {
        if($locale == '') $locale = Locale::getDefault();
        if($this->hasFile($type, $locale)){
            $this->asset->where('type', $type)->first()->uploadToAsset($file,$locale);
        }else{
            Asset::upload($file, $type, $locale)->attachToModel($this);
        }
    }


}