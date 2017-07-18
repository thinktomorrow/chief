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

        if(is_array($files)){
            $list = collect([]);
            collect($files)->each(function($file) use ($list){
                $self = new self();
                $self->save();

                $self->addMedia($file)->toMediaLibrary();

                $list->push($self);
            });

            return $list;
        }elseif($files instanceof File || $files instanceof \Illuminate\Http\Testing\File){
            $self = new self();
            $self->save();

            $self->addMedia($files)->toMediaLibrary();

            return $self;
        }

        return null;
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

    public function assets()
    {
        return $this->morphTo();
    }

    public static function remove($image_ids)
    {
        if(is_array($image_ids)){
            foreach($image_ids as $id){
                Asset::where('id', $id)->first()->delete();
            }
        }else{
            Asset::find($image_ids)->first()->delete();
        }
    }

    public static function getAllMedia()
    {
        $library = collect([]);

        self::all()->each(function ($asset) use ($library){
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