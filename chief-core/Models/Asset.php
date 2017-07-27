<?php

namespace Chief\Models;


use Chief\Locale\Locale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Asset extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    /**
     * Uploads the file/files or asset by creating the
     * asset that is needed to upload the files too
     *
     * @param $files
     * @return Asset|\Illuminate\Support\Collection|null
     */
    public static function upload($files)
    {
        $list = collect([]);

        if (is_array($files)) {
            collect($files)->each(function($file) use($list){
                $self = new self();
                $self->save();
                $list->push($self->uploadToAsset($file));
            });
        }elseif($files instanceof Asset)
        {

            return $files;
        }else{
            $self = new self();
            $self->save();
            return $self->uploadToAsset($files);
        }
        return $list;
    }

    /**
     * Uploads the given file to this instance of asset
     * and sets the dimensions as a custom property
     *
     * @param $files
     * @return $this|null
     */
    public function uploadToAsset($files)
    {
        if ($files instanceof File || $files instanceof \Illuminate\Http\Testing\File || $files instanceof UploadedFile) {
            $customProps = [];
            if(self::isImage($files))
            {
                $customProps['dimensions'] = getimagesize($files)[0] . ' x ' . getimagesize($files)[1];
            }

            $this->addMedia($files)->withCustomProperties($customProps)->toMediaCollection();
            return $this->load('media');
        }

        return null;
    }

    private static function isImage($file)
    {
        if (filesize($file) > 11)
        {
            return !!exif_imagetype($file);
        }
        else
        {
            return false;
        }
    }

    /**
     * Attaches this asset instance to the given model and
     * sets the type and locale to the given values and
     * returns the model with the asset relationship
     *
     * @param Model $model
     * @param string $type
     * @param null $locale
     * @return Model
     */
    public function attachToModel(Model $model, $type = '', $locale = null)
    {
        $asset = $model->assets->where('pivot.type', $type)->where('pivot.locale', $locale);

        if(!$asset->isEmpty()){
            $model->assets()->detach($asset->first()->id);
        }

        if(!$locale) {
            $locale = Locale::getDefault();
        }

        $model->assets()->attach($this, ['type' => $type, 'locale'=> $locale]);

        return $model->load('assets');
    }

    public function hasFile($locale = null)
    {
        return !! $this->getFileUrl('', $locale, true);
    }

    public function getFilename($size = '', $locale = null)
    {
        return basename($this->getFileUrl($size, $locale));
    }

    public function getFileUrl($size = '')
    {
        $media = $this->getMedia();

        if($media->count() >= 1){
            $media = $media->first();
        }else{
            return '../assets/back/img/other.png';
        }
        return $media->getUrl($size);
    }

    /**
     * Returns the image url or a fallback specific per filetype
     *
     * @param string $type
     * @return string
     */
    public function getImageUrl($type = '')
    {
        if($this->getMedia()->isEmpty()){
            return "../assets/back/img/other.png";
        }
        $url = $this->getMedia()[0]->getUrl();
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        if ($ext == 'pdf') {
            return "../assets/back/img/pdf.png";
        }
        elseif (in_array($ext, ['xls', 'xlsx', 'numbers', 'sheets'])) {
            return "../assets/back/img/xls.png";
        }
        elseif (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'])) {
            return $this->getFileUrl($type);
        }
        else{
            return "../assets/back/img/other.png";
        }
    }

    public function getExtensionForFilter()
    {
        $mimetype = $this->getMimeType();
        if(explode("/", $mimetype, 2)[0] == 'image') return 'image';
        if(explode("/", $mimetype, 2)[1] == 'pdf') return 'pdf';
        if(in_array(explode("/", $mimetype, 2)[1], ['xls', 'xlsx', 'numbers', 'sheets'])) return 'excel';

        return '';
    }

    public function getMimeType()
    {
        if($this->getMedia()->isEmpty()){
            return '';
        }
        return $this->getMedia()[0]->mime_type;
    }

    public function getSize()
    {
        if($this->getMedia()->isEmpty()){
            return '';
        }
        return $this->getMedia()[0]->human_readable_size;
    }

    public function getDimensions()
    {
        if($this->getMedia()->isEmpty()){
            return '';
        }
        return $this->getMedia()[0]->hasCustomProperty('dimensions') ? $this->getMedia()[0]->getCustomProperty('dimensions') : '/';
    }

    /**
     * Removes one or more assets by their ids
     * @param $image_ids
     */
    public static function remove($image_ids)
    {
        if (is_array($image_ids)) {
            foreach ($image_ids as $id) {
                Asset::where('id', $id)->first()->delete();
            }
        } else {
            Asset::find($image_ids)->first()->delete();
        }
    }


    /**
     * Returns a collection of all the assets in the library.
     * @return \Illuminate\Support\Collection
     */
    public static function getAllAssets()
    {
        return self::all()->sortByDesc('created_at');
    }

    /**
     * Generates the hidden field that links the file to a specific type.
     *
     * @param string $type
     * @param null $locale
     *
     * @return string
     */
    public static function typeField($type = '', $locale = null)
    {
        if(!$locale){
            return '<input type="hidden" value="' . $type . '" name="type">';
        }else{
            return '<input type="hidden" value="' . $type . '" name="trans['.$locale.'][files][]">';
        }
    }

    /**
     * Generates the hidden field that links the file to translations.
     *
     * @param string $locale
     *
     * @return string
     */
    public static function localeField($locale = '')
    {
        return '<input type="hidden" value="' . $locale . '" name="locale">';
    }

    /**
     * Register the conversions that should be performed.
     *
     * @return array
     */
    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(15)
            ->format('png')
            ->optimize();

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(130)
            ->sharpen(15)
            ->format('png')
            ->optimize();

        $this->addMediaConversion('large')
            ->width(1024)
            ->height(353)
            ->sharpen(15)
            ->format('png')
            ->optimize();

        $this->addMediaConversion('full')
            ->width(1600)
            ->height(553)
            ->sharpen(15)
            ->format('png')
            ->optimize();

//        $this->addMediaConversion('thumb')
//            ->setManipulations(['w' => 368, 'h' => 232])
//            ->performOnCollections('pdf');
    }

}