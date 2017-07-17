<?php

namespace Chief\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
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
                $self->optimize();

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

    public function getPath($collection = '')
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

    private function optimize()
    {
        app(OptimizerChain::class)->optimize($this->getMedia()[0]->getPath());
        app(OptimizerChain::class)->optimize($this->getMedia()[0]->getPath('thumb'));
        app(OptimizerChain::class)->optimize($this->getMedia()[0]->getPath('medium'));
        app(OptimizerChain::class)->optimize($this->getMedia()[0]->getPath('large'));
        app(OptimizerChain::class)->optimize($this->getMedia()[0]->getPath('full'));

    }

    /**
     * Register the conversions that should be performed.
     *
     * @return array
     */
    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 150, 'h' => 150, 'sharp' => 15, 'fm' => 'png']);

        $this->addMediaConversion('medium')
            ->setManipulations(['w' => 300, 'h' => 130, 'sharp' => 15, 'fm' => 'png']);

        $this->addMediaConversion('large')
            ->setManipulations(['w' => 1024, 'h' => 353, 'sharp' => 15, 'fm' => 'png']);

        $this->addMediaConversion('full')
            ->setManipulations(['w' => 1600, 'h' => 553, 'sharp' => 15, 'fm' => 'png']);
    }
}