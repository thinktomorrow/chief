<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\Chief\Urls\MemoizedUrlRecord;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ResolvingRoute;
use Thinktomorrow\Chief\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Audit\AuditTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Thinktomorrow\Chief\Concerns\Featurable;
use Thinktomorrow\Chief\Menu\ActsAsMenuItem;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\Snippets\WithSnippets;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\Relations\ActingAsParent;
use Thinktomorrow\Chief\Concerns\Morphable\Morphable;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Concerns\Archivable\Archivable;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Thinktomorrow\Chief\Concerns\Publishable\Publishable;
use Thinktomorrow\Chief\Concerns\Translatable\Translatable;
use Thinktomorrow\Chief\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableContract;
use Thinktomorrow\Chief\Urls\UrlRecordNotFound;

class Page extends Model implements ManagedModel, TranslatableContract, HasMedia, ActsAsParent, ActsAsChild, ActsAsMenuItem, MorphableContract, ViewableContract, ProvidesUrl
{
    use BaseTranslatable {
        getAttribute as getTranslatableAttribute;
    }

    use Morphable,
        AssetTrait,
        Translatable,
        SoftDeletes,
        Publishable,
        Featurable,
        Archivable,
        AuditTrait,
        ActingAsParent,
        ActingAsChild,
        WithSnippets,
        ResolvingRoute,
        Viewable;

    // Explicitly mention the translation model so on inheritance the child class uses the proper default translation model
    protected $translationModel      = PageTranslation::class;
    protected $translationForeignKey = 'page_id';
    protected $translatedAttributes  = [
        'title', 'content', 'short', 'seo_title', 'seo_description', 'seo_keywords', 'seo_image'
    ];

    public $table          = "pages";
    protected $guarded     = [];
    protected $with        = ['translations'];

    protected $baseViewPath;
    protected static $baseUrlSegment = '/';

    public function __construct(array $attributes = [])
    {
        $this->constructWithSnippets();

        if (!isset($this->baseViewPath)) {
            $this->baseViewPath = config('thinktomorrow.chief.base-view-paths.pages', 'pages');
        }

        parent::__construct($attributes);
    }

    public static function managedModelKey(): string
    {
        if (isset(static::$managedModelKey)) {
            return static::$managedModelKey;
        }

        throw new \Exception('Missing required static property \'managedModelKey\' on ' . static::class. '.');
    }

    /**
     * Parse and render any found snippets in custom
     * or translatable attribute values.
     *
     * @param string $value
     * @return mixed|null|string|string[]
     */
    public function getAttribute($value)
    {
        $value = $this->getTranslatableAttribute($value);

        if ($this->shouldParseWithSnippets($value)) {
            $value = $this->parseWithSnippets($value);
        }

        return $value;
    }

    /**
     * Page specific modules. We exclude text modules since they are modules in pure
     * technical terms and not so much as behavioural elements for the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules()
    {
        return $this->hasMany(Module::class, 'page_id')->where('morph_key', '<>', 'text');
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(static::class, $this->id);
    }

    public function flatReferenceLabel(): string
    {
        if ($this->exists) {
            $status = ! $this->isPublished() ? ' [' . $this->statusAsPlainLabel().']' : null;

            return $this->title ? $this->title . $status : '';
        }

        return '';
    }

    public function flatReferenceGroup(): string
    {
        $classKey = get_class($this);
        $labelSingular = property_exists($this, 'labelSingular') ? $this->labelSingular : str_singular($classKey);

        return $labelSingular;
    }

    public function mediaUrls($type = null): Collection
    {
        // TODO getallfiles should actually get all files...
        // What was the creator of the assetlibrary package thinking. It sure wasn't me... I promise...
        $assets = $this->getAllFiles($type, app()->getLocale())->map->getFileUrl();

        if ($assets->first() == null) {
            $assets = $this->getAllFiles($type)->map->getFileUrl();
        }

        return $assets;
    }

    public function mediaUrl($type = null): ?string
    {
        return $this->mediaUrls($type)->first();
    }

    public static function findPublished($id)
    {
        return static::published()->find($id);
    }

    public function scopeSortedByCreated($query)
    {
        $query->orderBy('created_at', 'DESC');
    }

    /** @inheritdoc */
    public function url(string $locale = null): string
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        try {
            $slug = MemoizedUrlRecord::findByModel($this, $locale)->slug;

            return $this->resolveUrl($locale, [$slug]);
        } catch (UrlRecordNotFound $e) {
            return '';
        }
    }

    public function resolveUrl(string $locale = null, $parameters = null): string
    {
        $routeName = config('thinktomorrow.chief.route.name');

        return $this->resolveRoute($routeName, $parameters, $locale);
    }

    /** @inheritdoc */
    public function previewUrl(string $locale = null): string
    {
        return $this->url($locale).'?preview-mode';
    }


    /** @inheritdoc */
    public static function baseUrlSegment(string $locale = null): string
    {
        if (!isset(static::$baseUrlSegment)) {
            return '/';
        }

        if (!is_array(static::$baseUrlSegment)) {
            return static::$baseUrlSegment;
        }

        // When an array, we try to locate the expected segment by locale
        $key = $locale ?? app()->getlocale();

        if (isset(static::$baseUrlSegment[$key])) {
            return static::$baseUrlSegment[$key];
        }

        $fallback_locale = config('app.fallback_locale');
        if (isset(static::$baseUrlSegment[$fallback_locale])) {
            return static::$baseUrlSegment[$fallback_locale];
        }

        // Fall back to first entry in case no match is found
        return reset(static::$baseUrlSegment);
    }

    public function menuLabel(): string
    {
        return $this->title ?? '';
    }

    /**
     * We override the publishable trait defaults because Page needs
     * to be concerned with the archived state as well.
     *
     * TODO: IMPROVEMENT SHOULD BE TO MANAGE THE PAGE STATES IN ONE LOCATION. eg state machine
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
            return '<a href="'.$this->url().'" target="_blank"><em>online</em></a>';
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
