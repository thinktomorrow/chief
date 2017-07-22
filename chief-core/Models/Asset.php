<?php

namespace Chief\Models;


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

    public static function upload($files, $collection = '')
    {
        $list = collect([]);

        if (is_array($files)) {
            collect($files)->each(function($file) use($list, $collection){
                $self = new self();
                $self->save();
                $list->push($self->uploadToAsset($file, $collection));
            });
        }else{
            $self = new self();
            $self->save();
            return $self->uploadToAsset($files, $collection);
        }
        return $list;
    }

    public function uploadToAsset($files, $collection = '', $replace = false)
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
            $customProps = [];
            if(self::isImage($files))
            {
                $customProps = ['dimensions' => getimagesize($files)[0] . ' x ' . getimagesize($files)[1] ];
            }
            if($collection)
            {
                $customProps = ['type' => $collection];
            }
            if($replace)
            {
                $this->getAllMedia()->reject(function ($asset) use($collection){
                    return $asset->getFileType() != $collection;
                })->each(function($asset){
                    $asset->getMedia()[0]->delete();
                });
            }
            $media = $this->addMedia($files)->withCustomProperties($customProps)->toMediaCollection();
            return $this;
        }

        return null;
    }

    private static function isImage($file)
    {
        if (filesize($file) > 11)
        {
            return exif_imagetype($file);

        }
        else
        {
            return false;

        }
    }

    public function attachToModel(Model $model, $replace = false)
    {
        if($replace)
        {
            $this->getAllMedia()->reject(function ($asset){
                return $asset->model_id == null;
            })->each(function($asset){
                $asset->delete();
            });

            $model->asset()->save($this);
        }else{
            $model->asset()->save($this);
        }

        return $this;
    }

    public function hasFile($collection = '')
    {
        return !! $this->getFileUrl($collection);
    }

    public function getFilename($collection = '')
    {
        return basename($this->getFileUrl($collection));
    }

    public function getFileUrl($collection = '')
    {
        $filename = '../assets/back/img/other.png';
        $this->getMedia()->each(function ($media) use($collection, &$filename){
            if($media->getCustomProperty('type') == $collection){
                $filename =  $media->getUrl();
            }
        });
        return $filename;
    }

    public function getPath()
    {
        return $this->getPathForSize();
    }

    public function getPathForSize($collection = '')
    {
        if($this->getMedia()->isEmpty()){
            return '';
        }
        return $this->getMedia()[0]->getUrl($collection);
    }

    public function getImageUrl($collection = '')
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
            return $this->getPathForSize($collection);
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



    public static function getAllMedia()
    {
        $library = collect([]);

        self::all()->each(function ($asset) use ($library) {
            $library->push($asset);
        });

        return $library;
    }

    public function getFileType()
    {
        return $this->getMedia()[0]->getCustomProperty('type');
    }

    /**
     * Generates the hidden field that links the file to a specific collection.
     *
     * @param string $collection
     *
     * @return string
     */
    public static function collectionField($collection = '')
    {
        return '<input type="hidden" value="' . $collection . '" name="collection">';
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