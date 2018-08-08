<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Collections\ActsAsCollection;
use Thinktomorrow\Chief\Common\Collections\ActingAsCollection;
use Thinktomorrow\Chief\Common\Relations\ActingAsChild;
use Thinktomorrow\Chief\Common\Relations\ActingAsParent;
use Thinktomorrow\Chief\Common\Relations\ActsAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Common\Translatable\Translatable;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\Common\Traits\Featurable;
use Thinktomorrow\Chief\Common\Traits\Archivable\Archivable;
use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;
use Thinktomorrow\Chief\Common\Audit\AuditTrait;
use Thinktomorrow\Chief\Menu\ActsAsMenuItem;
use Thinktomorrow\Chief\Common\Publish\Publishable;
use Thinktomorrow\Chief\Modules\Module;

class Page extends Model implements TranslatableContract, HasMedia, ActsAsParent, ActsAsChild, ActsAsMenuItem, ActsAsCollection
{
    use ActingAsCollection,
        AssetTrait,
        Translatable,
        BaseTranslatable,
        SoftDeletes,
        Publishable,
        Featurable,
        Archivable,
        AuditTrait,
        ActingAsParent,
        ActingAsChild;

    // Explicitly mention the translation model so on inheritance the child class uses the proper default translation model
    protected $translationModel = PageTranslation::class;
    protected $translationForeignKey = 'page_id';
    protected $translatedAttributes = [
        'slug', 'title', 'seo_title', 'seo_description'
    ];

    public $table = "pages";
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected $with = ['translations'];

    public function __construct(array $attributes = [])
    {
        $this->translatedAttributes = array_merge($this->translatedAttributes, array_keys(static::translatableFields()));

        parent::__construct($attributes);
    }

    /**
     * page specific modules. We exclude text modules since they are modules in pure
     * technical terms and not so much as behaviour element for the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules()
    {
        return $this->hasMany(Module::class, 'page_id')->where('collection', '<>', 'text');
    }

    /**
     * Each page model can expose the managed translatable fields. These should be included as attributes just like the regular
     * translatable attributes. This method allows for easy installation of the form fields in chief.
     *
     * @param null $key
     * @return array
     */
    final public static function translatableFields($key = null)
    {
        $translatableFields = array_merge(static::defaultTranslatableFields(), static::customTranslatableFields());

        return $key ? array_pluck($translatableFields, $key) : $translatableFields;
    }

    /**
     * The custom addition of fields for a page model.
     *
     * To add a field, you should:
     * 1. override this method with your own and return the comprised list of fields.
     * 2. Setup the proper migrations and add the new field to the translatable values of the collection.
     *
     * @return array
     */
    public static function customTranslatableFields(): array
    {
        return [];
    }

    /**
     * The default set of fields for a page model.
     *
     * If you wish to remove any of these fields, you should:
     * 1. override this method with your own and return the comprised list of fields.
     * 2. Provide a migration to remove the column from database and remove the fields from the translatable values of the model.
     *
     * Note that the following translated fields are always present and cannot be removed:
     * - title
     * - slug
     * - seo_title
     * - seo_description
     *
     * @return array
     */
    public static function defaultTranslatableFields(): array
    {
        return [

        ];
    }

    /**
     * Each page model can expose the managed media fields.
     * This method allows for easy installation of the form fields in chief.
     *
     * @param null $key
     * @return array
     */
    final public static function mediaFields($key = null)
    {
        $mediaFields = array_merge(static::defaultMediaFields(), static::customMediaFields());

        return $key ? array_pluck($mediaFields, $key) : $mediaFields;
    }

    /**
     * The custom addition of media fields for a page model.
     *
     * To add a field, you should:
     * 1. override this method with your own and return the comprised list of fields.
     *
     * @return array
     */
    public static function customMediaFields(): array
    {
        return [];
    }

    /**
     * The default set of media fields for a page model.
     *
     * If you wish to remove any of these fields, you should:
     * 1. override this method with your own and return the comprised list of fields.
     *
     * @return array
     */
    public static function defaultMediaFields(): array
    {
        return [
            // MediaType::HERO => [
            //     'type' => MediaType::HERO,
            //     'is_document' => false,
            //     'label' => 'Hoofdafbeelding',
            //     'description' => '',
            // ],
        ];
    }

    public function flatReferenceLabel(): string
    {
        $status = ! $this->isPublished() ? ' [' . $this->statusAsPlainLabel().']' : null;

        return $this->title ? $this->title . $status : '';
    }

    public function flatReferenceGroup(): string
    {
        return $this->collectionDetails()->singular;
    }

    public function mediaUrls($type = null): Collection
    {
        return $this->getAllFiles($type)->map->getFileUrl();
    }

    public function mediaUrl($type = null): ?string
    {
        return $this->mediaUrls($type)->first();
    }

    public static function findPublished($id)
    {
        return (($page = self::published()->find($id)))
            ? $page
            : null;
    }

    public static function findBySlug($slug)
    {
        return ($trans = PageTranslation::findBySlug($slug)) ? static::find($trans->page_id) : null;
    }

    public static function findPublishedBySlug($slug)
    {
        $translationModel = (new static)->translationModel;

        return ($trans =  $translationModel::findBySlug($slug)) ? static::findPublished($trans->page_id) : null;
    }

    public function scopeSortedByCreated($query)
    {
        $query->orderBy('created_at', 'DESC');
    }

    public function previewUrl()
    {
        // TODO: how we allow for these default routes to be set up in every new project?
//        return '';
        return route('pages.show', $this->slug).'?preview-mode';
    }

    public function menuUrl(): string
    {
        // TODO: how we allow for these default routes to be set up in every new project?
//        return '';
        return route('pages.show', $this->slug);
    }

    public function menuLabel(): string
    {
        return $this->title;
    }

    public function isHomepage(): bool
    {
        $homepage_id = config('thinktomorrow.chief-settings.homepage_id');

        return $this->id == $homepage_id;
    }

    public static function guessHomepage(): self
    {
        $homepage_id = config('thinktomorrow.chief-settings.homepage_id');

        // Homepage id is explicitly set
        if ($homepage_id && $page = static::findPublished($homepage_id)) {
            return $page;
        }

        if ($page = static::collection('singles')->published()->first()) {
            return $page;
        }

        if ($page = static::published()->first()) {
            return $page;
        }

        throw new NotFoundHomepage('No homepage could be guessed. Make sure to provide a published page and set its id in the thinktomorrow.chief-settings.homepage_id config parameter.');
    }

    public function view()
    {
        $viewPaths = [
            'front.'.$this->collectionKey().'.show',
            'front.pages.'.$this->collectionKey().'.show',
            'front.pages.show'
        ];

        foreach ($viewPaths as $viewPath) {
            if (! view()->exists($viewPath)) {
                continue;
            }

            return view($viewPath, [
                'page' => $this,
            ]);
        }

        throw new NotFoundView('Frontend view could not be determined for page. Make sure to at least provide a front.pages.show viewfile.');
    }

    /**
     * PUBLISHABLE OVERRIDES BECAUSE OF ARCHIVED STATE IS SET ELSEWHERE.
     * IMPROVEMENT SHOULD BE TO MANAGE THE PAGE STATES IN ONE LOCATION. eg state machine
     */
    public function isPublished()
    {
        return (!!$this->published && is_null($this->archived_at));
    }

    public function isDraft()
    {
        return (!$this->published && is_null($this->archived_at));
    }

    public function publish()
    {
        $this->published = 1;
        $this->archived_at = null;

        $this->save();
    }

    public function draft()
    {
        $this->published = 0;
        $this->archived_at = null;

        $this->save();
    }

    public function statusAsLabel()
    {
        if ($this->isPublished()) {
            return '<a href="'.$this->menuUrl().'" target="_blank"><em>online</em></a>';
        }

        if ($this->isDraft()) {
            return '<a href="'.$this->previewUrl().'" target="_blank" class="text-error"><em>offline</em></a>';
        }

        if ($this->isArchived()) {
            return '<span><em>gearchiveerd</em></span>';
        }

        return '-';
    }

    public function statusAsPlainLabel()
    {
        if ($this->isPublished()) {
            return 'online';
        }

        if ($this->isDraft()) {
            return 'offline';
        }

        if ($this->isArchived()) {
            return 'gearchiveerd';
        }

        return '-';
    }
}
