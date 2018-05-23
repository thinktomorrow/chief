<?php

namespace Chief\Pages;


use Chief\Common\Relations\ActingAsChild;
use Chief\Common\Relations\ActingAsParent;
use Chief\Common\Relations\ActsAsChild;
use Chief\Common\Relations\ActsAsParent;
use Chief\Common\Relations\Relation;
use Chief\Common\Translatable\Translatable;
use Chief\Common\Translatable\TranslatableContract;
use Chief\Common\Traits\Publishable;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Chief\Common\Traits\Featurable;
use Chief\Common\Traits\Archivable;

class Page extends Model implements TranslatableContract, HasMedia, ActsAsParent, ActsAsChild
{
    use AssetTrait, Translatable, BaseTranslatable, SoftDeletes, Publishable, Featurable, ActingAsParent, ActingAsChild, Archivable;

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

    public function presentForParent(ActsAsParent $parent, Relation $relation): string
    {
        return 'Dit is de relatie weergave van een pagina onder ' . $parent->id;
    }

    public function presentForChild(ActsAsChild $child, Relation $relation): string
    {
        return 'Dit is de relatie weergave van een pagina als parent voor ' . $child->id;
    }

    public function getRelationId(): string
    {
        return $this->getMorphClass().'@'.$this->id;
    }

    public function getRelationLabel(): string
    {
        return $this->title;
    }

    public function getRelationGroup(): string
    {
        return 'pages';
    }
}