<?php

namespace Chief\Pages;


use Chief\Common\Relations\ActingAsChild;
use Chief\Common\Relations\ActingAsParent;
use Chief\Common\Relations\ActsAsChild;
use Chief\Common\Relations\ActsAsParent;
use Chief\Common\Translatable\Translatable;
use Chief\Common\Translatable\TranslatableContract;
use Chief\Common\Traits\Publishable;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Chief\Common\Traits\Featurable;

class Page extends Model implements TranslatableContract, HasMedia, ActsAsParent, ActsAsChild
{
    use AssetTrait, Translatable, BaseTranslatable, SoftDeletes, Publishable, Featurable, ActingAsParent, ActingAsChild;

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

    public function scopeSortedByRecent($query)
    {
        $query->orderBy('created_at','DESC');
    }
}