<?php

namespace Chief\Pages;


use Chief\Common\Translatable\Translatable;
use Chief\Common\Translatable\TranslatableContract;
use Chief\Common\Traits\Publishable;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;

class Page extends Model implements TranslatableContract, HasMedia
{
    use AssetTrait, Translatable, BaseTranslatable, SoftDeletes, Publishable;

    protected $translatedAttributes = [
        'slug', 'title', 'content', 'short', 'seo_title', 'seo_description'
    ];

    protected $dates = ['deleted_at'];
    protected $with = ['translations'];

    public static function findBySlug($slug)
    {
        return ($trans = PageTranslation::findBySlug($slug)) ? $trans->page()->first() : null;
    }

    public static function findPublishedBySlug($slug)
    {
        return ($trans = PageTranslation::findBySlug($slug)) ? $trans->page()->published()->first() : null;
    }

    public static function getAll()
    {
        return self::published()->get();
    }

    public function scopeSortedByPublished($query)
    {
        $query->orderBy('published','DESC');
    }

    public function scopeSortedByRecent($query)
    {
        $query->orderBy('created_at','DESC');
    }
}