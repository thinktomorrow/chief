<?php

namespace Chief\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Spatie\Image\Image;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\ImageOptimizer\OptimizerChain;

class Asset extends Model implements HasMediaConversions
{

    use HasMediaTrait;

    public static function upload($files)
    {
        if (is_array($files)) {
            $list = collect([]);
            collect($files)->each(function ($file) use ($list) {
                $self = new self();
                $self->save();
                $dimensions = [];
                if(self::isImage($file))
                {
                    $dimensions = ['dimensions' => getimagesize($file)[0] . ' x ' . getimagesize($file)[1] ];
                }
                $self->addMedia($file)->withCustomProperties($dimensions)->toMediaCollection();

                $list->push($self);
            });

            return $list;
        } elseif ($files instanceof File || $files instanceof \Illuminate\Http\Testing\File) {
            $self = new self();
            $self->save();
            $dimensions = [];
            if(self::isImage($files))
            {
                $dimensions = ['dimensions' => getimagesize($files)[0] . ' x ' . getimagesize($files)[1] ];
            }
            $self->addMedia($files)->withCustomProperties($dimensions)->toMediaCollection();

            return $self;
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

    public function attachToModel(Model $model)
    {
        $model->asset()->save($this);

        return $this;
    }

    public function getFilename()
    {
        return $this->getMedia()[0]->file_name;
    }

    public function getPath()
    {
        return $this->getPathForSize();
    }

    public function getPathForSize($collection = '')
    {
        return $this->getMedia()[0]->getUrl($collection);
    }

    public function getImageUrl($collection = '')
    {
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
        return $this->getMedia()[0]->mime_type;
    }

    public function getSize()
    {
        return $this->getMedia()[0]->human_readable_size;
    }

    public function getDimensions()
    {
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