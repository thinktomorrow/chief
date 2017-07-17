<?php

namespace Chief\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Asset extends Model implements HasMediaConversions
{

    use HasMediaTrait;

    public static function upload($file)
    {
        $self = new self();
        $self->save();

        $self->addMedia($file)->toMediaLibrary();

        return $self;
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
        return $this->getMedia()[0]->getUrl();
    }

    public function assets()
    {
        return $this->morphTo();
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
        // TODO: Implement registerMediaConversions() method.
    }
}