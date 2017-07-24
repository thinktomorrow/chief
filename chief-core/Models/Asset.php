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

    public static $conversions = [
        'thumb' => [
            'width'     => 150,
            'height'    => 150,
        ],
        'medium' => [
            'width'     => 300,
            'height'    => 130,
        ],
        'large' => [
            'width'     => 1024,
            'height'    => 353,
        ],
        'full' => [
            'width'     => 1600,
            'height'    => 553,
        ]
    ];

    public static function upload($files, $type = '', $locale = '')
    {
        $list = collect([]);

        if (is_array($files)) {
            collect($files)->each(function($file) use($list, $type, $locale){
                $self = new self();
                $self->type = $type;
                $self->save();
                $list->push($self->uploadToAsset($file, $locale));
            });
        }elseif($files instanceof Asset)
        {
            $clone = $files->replicate();
            $clone->save();
            $clone->type = $type;
            $clone->load('media');
            $media = $files->media->first()->replicate();
            $media->model_id = $clone->model_id;
            $media->save();
            dd($clone->id, $media);
            $clone->media->first()->setCustomProperty('locale', $locale);
            $clone->save();
            return $clone;
        }else{
            $self = new self();
            $self->type = $type;
            $self->save();
            return $self->uploadToAsset($files, $locale);
        }
        return $list;
    }

    public function getModel()
    {
        dd($this);
    }

    public function uploadToAsset($files, $locale = '')
    {

        if (is_array($files)) {
            //Can't do multiple uploads linked to one asset at this time

            //            $list = collect([]);
//            collect($files)->each(function ($file) use ($list, $collection) {
//                $customProps = [];
//                if(self::isImage($file))
//                {
//                    $customProps = ['dimensions' => getimagesize($file)[0] . ' x ' . getimagesize($file)[1] ];
//                }
//                if($collection)
//                {
//                    $customProps = ['type' => $collection];
//                }
//                $media = $this->addMedia($file)->withCustomProperties($customProps)->toMediaCollection();
//
//                $list->push($this);
//            });
//
//            return $list;
            return $files;
        } elseif ($files instanceof File || $files instanceof \Illuminate\Http\Testing\File || $files instanceof UploadedFile) {
            $this->media->filter(function($media) use($locale){
                return $media->getCustomProperty('locale') == $locale;
            })->each(function ($media){
                return $media->delete();
            });
            $customProps = [];
            if(self::isImage($files))
            {
                $customProps['dimensions'] = getimagesize($files)[0] . ' x ' . getimagesize($files)[1];
            }
            if($locale)
            {
                $customProps['locale'] = $locale;
            }else{
                $customProps['locale'] = Locale::getDefault();
            }

            $this->addMedia($files)->withCustomProperties($customProps)->toMediaCollection();

            $this->refresh();
            return $this;
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

    public function attachToModel(Model $model)
    {
        $model->asset()->save($this);

        return $model->load('asset');
    }

    public function hasFile($locale = null)
    {
        return !! $this->getFileUrl('', $locale);
    }

    public function getFilename($size = '', $locale = null)
    {
        return basename($this->getFileUrl($size, $locale));
    }

    public function getFileUrl($size = '', $locale = null)
    {
        if(!$locale){
            $locale = Locale::getDefault();
        }
        $media = $this->getMedia()->filter(function($media) use($locale){
            return $media->getCustomProperty('locale') == $locale || $media->getCustomProperty('locale') == Locale::getDefault();
        });

        if($media->count() > 1)
        {
            $media = $media->filter(function($media) use($locale){
                return $media->getCustomProperty('locale') == $locale;
            })->first();
        }elseif($media->count() == 1){
            $media = $media->first();
        }else{
            return '../assets/back/img/other.png';

        }
        return $media->getUrl($size);
    }

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

//    public function assets()
//    {
//        return $this->morphTo();
//    }

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



    public static function getAllAssets()
    {
        $library = collect([]);

        self::all()->each(function ($asset) use ($library) {
            $library->push($asset);
        });

        return $library;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Generates the hidden field that links the file to a specific type.
     *
     * @param string $type
     *
     * @return string
     */
    public static function typeField($type = '')
    {
        return '<input type="hidden" value="' . $type . '" name="type">';
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