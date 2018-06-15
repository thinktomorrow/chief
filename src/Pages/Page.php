<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Support\Collection;
use Thinktomorrow\AssetLibrary\Models\Asset;
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
use Thinktomorrow\Chief\Common\Traits\Archivable\Archivable;
use Thinktomorrow\Chief\Media\MediaType;

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

    /**
     * Set a custom morph class for the morph relations because we
     * mostly want the Page as morph relationship instead of the
     * child class.
     */
    public function getMorphClass()
    {
        return self::class;
    }

    public function getOwnMorphClass()
    {
        return parent::getMorphClass();
    }

    public static function findBySlug($slug)
    {
        return ($trans = PageTranslation::findBySlug($slug)) ? $trans->pageWithoutCollectionScope()->first() : null;
    }

    public static function findPublishedBySlug($slug)
    {
        return ($trans = PageTranslation::findBySlug($slug)) ? $trans->pageWithoutCollectionScope()->published()->first() : null;
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
        return $this->collection;
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

    public static function flattenForSelect()
    {
        return self::ignoreCollection()->get()->map(function (Page $page) {
            return [
                'id'    => $page->getRelationId(),
                'label' => $page->getRelationLabel(),
                'group' => $page->getRelationGroup(),
            ];
        });
    }

    public static function flattenForGroupedSelect(): Collection
    {
        $grouped = [];

        self::flattenForSelect()->each(function ($entry) use (&$grouped) {
            if (isset($grouped[$entry['group']])) {
                $grouped[$entry['group']]['values'][] = $entry;
            } else {
                $grouped[$entry['group']] = ['group' => $entry['group'], 'values' => [$entry]];
            }
        });

        // We remove the group key as we need to have non-assoc array for the multiselect options.
        return collect(array_values($grouped));
    }

    public static function inflate($relateds = []): self
    {
        if (!is_array($relateds)) {
            $relateds = [$relateds];
        }

        if (count($relateds) == 1 && is_null(reset($relateds))) {
            $relateds = [];
        }

        return (collect($relateds))->map(function ($related) {
            list($type, $id) = explode('@', $related);

            return (new $type)->find($id);
        })->first();
    }

    public function mediaUrls($type = null): Collection
    {
        return $this->getAllFiles($type)->map->getFileUrl();
    }

    public function mediaUrl($type = null): string
    {
        return $this->mediaUrls($type)->first();
    }
    
    public static function availableMediaTypes($key = null)
    {
        $types = [
            MediaType::HERO => [
                'type' => MediaType::HERO,
                'label' => 'Hoofdafbeelding',
                'description' => '',
//                'limit' => 1,
            ],
            MediaType::THUMB => [
                'type' => MediaType::THUMB,
//                'limit' => 1,
                'label' => 'Thumbnails',
                'description' => '',
            ],
        ];

        return $key ? array_pluck($types, $key) : $types;
    }
}
