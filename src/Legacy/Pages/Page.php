<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Legacy\Pages;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\Shared\Concerns\Featurable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\Chief\Legacy\Fragments\HasFragments;
use Thinktomorrow\Chief\Site\Urls\MemoizedUrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphable;
use Thinktomorrow\Chief\ManagedModels\States\Archivable\Archivable;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\Publishable;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ResolvingRoute;
use Thinktomorrow\Chief\PageBuilder\Relations\ActsAsChild;
use Thinktomorrow\Chief\PageBuilder\Relations\ActsAsParent;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\Shared\Concerns\Translatable\Translatable;
use Thinktomorrow\Chief\PageBuilder\Relations\ActingAsChild;
use Astrotomic\Translatable\Translatable as BaseTranslatable;
use Thinktomorrow\Chief\PageBuilder\Relations\ActingAsParent;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\Shared\Concerns\Translatable\TranslatableContract;

class Page extends Model implements TranslatableContract, HasAsset, ActsAsParent, ActsAsChild, MorphableContract, ViewableContract, ProvidesUrl, StatefulContract
{
    use BaseTranslatable {
        getAttribute as getTranslatableAttribute;
    }

    use Morphable;
    use AssetTrait;
    use Translatable;
    use SoftDeletes;
    use Publishable;
    use Featurable;
    use Archivable;
    use ActingAsParent;
    use ActingAsChild;
    use ResolvingRoute;
    use Viewable;
    use HasFragments;

    // Explicitly mention the translation model so on inheritance the child class uses the proper default translation model
    protected $translationModel = PageTranslation::class;
    protected $translationForeignKey = 'page_id';
    protected $translatedAttributes = [
        'title',
        'content',
        'short',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_image',
    ];

    public $table = "pages";
    protected $guarded = [];
    protected $with = ['translations'];

    protected $baseViewPath;
    protected static $baseUrlSegment = '/';

    protected static $cachedUrls = [];

    public static function clearCachedUrls()
    {
        static::$cachedUrls = null;
    }

    final public function __construct(array $attributes = [])
    {
        if (!isset($this->baseViewPath)) {
            $this->baseViewPath = config('chief.base-view-paths.pages', 'pages');
        }

        parent::__construct($attributes);
    }

    public static function managedModelKey(): string
    {
        if (isset(static::$managedModelKey)) {
            return static::$managedModelKey;
        }

        throw new \Exception('Missing required static property \'managedModelKey\' on ' . static::class . '.');
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
        return $this->getTranslatableAttribute($value);
    }

    /**
     * Page specific modules. We exclude text modules since they are modules in pure
     * technical terms and not so much as behavioural elements for the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules()
    {
        return $this->morphMany(Module::class, 'owner')->where('morph_key', '<>', 'text');
    }

    public function modelReference(): ModelReference
    {
        return new ModelReference(static::class, $this->id);
    }

    public function modelReferenceLabel(): string
    {
        if ($this->exists) {
            $status = !$this->isPublished() ? ' [' . $this->statusAsPlainLabel() . ']' : null;

            return $this->title ? $this->title . $status : '';
        }

        return '';
    }

    public function modelReferenceGroup(): string
    {
        $classKey = get_class($this);
        if (property_exists($this, 'labelSingular')) {
            $labelSingular = $this->labelSingular;
        } else {
            $labelSingular = Str::singular($classKey);
        }

        return $labelSingular;
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
            $memoizedKey = $this->getMorphClass() . '-' . $this->id . '-' . $locale;

            if (isset(static::$cachedUrls[$memoizedKey])) {
                return static::$cachedUrls[$memoizedKey];
            }

            $slug = MemoizedUrlRecord::findByModel($this, $locale)->slug;

            return static::$cachedUrls[$memoizedKey] = $this->resolveUrl($locale, [$slug]);
        } catch (UrlRecordNotFound $e) {
            return '';
        }
    }

    public function resolveUrl(string $locale = null, $parameters = null): string
    {
        $routeName = config('chief.route.name');

        return $this->resolveRoute($routeName, $parameters, $locale);
    }

    /** @inheritdoc */
    public function baseUrlSegment(string $locale = null): string
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

    public function statusAsLabel()
    {
        if ($this->isPublished()) {
            return '<a href="' . $this->url() . '" target="_blank"><em>online</em></a>';
        }

        if ($this->isDraft()) {
            return '<a href="' . $this->url() . '" target="_blank" class="text-error"><em>offline</em></a>';
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

    public function stateOf($key): string
    {
        return $this->$key ?? PageState::DRAFT;
    }

    public function changeStateOf($key, $state)
    {
        // Ignore change to current state - it should not trigger events either
        if ($state === $this->stateOf($key)) {
            return;
        }

        PageState::assertNewState($this, $key, $state);

        $this->$key = $state;
    }
}
