<?php

namespace Thinktomorrow\Chief\Pages;

use Thinktomorrow\Chief\Common\Relations\ActingAsChild;
use Thinktomorrow\Chief\Common\Relations\ActingAsParent;
use Thinktomorrow\Chief\Common\Relations\ActsAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Common\Relations\Relation;
use Thinktomorrow\Chief\Common\Translatable\Translatable;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;
use Thinktomorrow\Chief\Common\Traits\Publishable;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\Common\Traits\Featurable;
use Thinktomorrow\Chief\Common\Traits\Archivable;

class Page extends Model implements TranslatableContract, HasMedia, ActsAsParent, ActsAsChild
{
    use HasCollection,
        AssetTrait,
        Translatable,
        BaseTranslatable,
        SoftDeletes,
        Publishable,
        Featurable,
        Archivable,
        ActingAsParent,
        ActingAsChild;

    // Explicitly mention the translation model so on inheritance the child class uses the proper default translation model
    protected $translationModel = PageTranslation::class;
    protected $translationForeignKey = 'page_id';
    protected $translatedAttributes = [
        'slug', 'title', 'content', 'short', 'seo_title', 'seo_description'
    ];

    public $table = "pages";
    protected $guarded = [];
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

    public function scopeSortedByCreated($query)
    {
        $query->orderBy('created_at', 'DESC');
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

    public function previewUrl()
    {
        // return route('pages.show', $this->slug).'?preview-mode';
    }

    /**
     * Details of the collection such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     *
     * @param null $key
     * @return object
     */
    public static function collectionDetails($key = null)
    {
        $collectionKey = (new static)->collectionKey();

        $names = (object) [
            'key'      => $collectionKey,
            'class'    => static::class,
            'singular' => $collectionKey == 'statics' ? 'pagina' : ucfirst(str_singular($collectionKey)),
            'plural'   => $collectionKey == 'statics' ? 'pagina\'s' : ucfirst(str_plural($collectionKey)),
        ];

        return $key ? $names->$key : $names;
    }
}
