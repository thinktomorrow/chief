<?php

namespace Chief\Models;


use Chief\Locale\Translatable;
use Chief\Locale\TranslatableContract;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Article extends Model implements TranslatableContract, HasMedia
{
    use AssetTrait, Translatable, BaseTranslatable, SoftDeletes, Publishable, HasMediaTrait;

    protected $table = 'articles';
    public $timestamps = true;
    protected $translatedAttributes = ['title','content','short','slug','meta_description'];

    protected $dates = ['deleted_at'];

    public function scopeSortedByPublished($query)
    {
        $query->orderBy('published','DESC');
    }

    public function scopeSortedByRecent($query)
    {
        $query->orderBy('created_at','DESC');
    }

    public static function findBySlug($slug)
    {
        return ($trans = ArticleTranslation::findBySlug($slug)) ? $trans->article()->first() : null;
    }

    public static function findPublishedBySlug($slug)
    {
        return ($trans = ArticleTranslation::findBySlug($slug)) ? $trans->article()->published()->first() : null;
    }

    public static function getAll()
    {
        return self::published()->get();
    }

    public function hasMedia(string $collection = 'default'): bool
    {
        return !$this->asset->isEmpty();
    }
}