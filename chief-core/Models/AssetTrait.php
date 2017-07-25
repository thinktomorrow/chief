<?php

namespace Chief\Models;

use Chief\Locale\Locale;

trait AssetTrait
{
    public function assets()
    {
        return $this->morphToMany(Asset::class, 'entity', 'asset_pivots')->withPivot('type', 'locale');
    }

    public function hasFile($type = '', $locale = '')
    {
        $filename = $this->getFilename($type, $locale);

        return !!$filename and basename($filename) != 'other.png';
    }

    public function getFilename($type = '', $locale = '')
    {
        return basename($this->getFileUrl($type, '', $locale));
    }

    public function getFileUrl($type = '', $size = '', $locale = null)
    {
        if ($this->assets->first() == null || $this->assets->first()->pivot == null){
            return null;
        }

        if(!$locale){
            $locale = Locale::getDefault();
        }

        $assets = $this->assets->where('pivot.type', $type);
        if($assets->count() > 1){
            $assets = $assets->where('pivot.locale', $locale);
        }

        if ($assets->isEmpty()) {
            return null;
        }

        return $assets->first()->getFileUrl($size);
    }

    /**
     * Adds a file to this model, accepts a type and locale to be saved with the file
     *
     * @param $file
     * @param $type
     * @param string $locale
     */
    public function addFile($file, $type = '', $locale = null)
    {
        if ($locale == '' || $locale == null) {
            $locale = Locale::getDefault();
        }

        Asset::upload($file)->attachToModel($this, $type, $locale);
    }


}