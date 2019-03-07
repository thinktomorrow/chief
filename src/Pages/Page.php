<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
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

class Page extends Model implements TranslatableContract, HasMedia, ActsAsParent, ActsAsChild, ActsAsMenuItem, MorphableContract
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
        WithSnippets;

    // Explicitly mention the translation model so on inheritance the child class uses the proper default translation model
    protected $translationModel      = PageTranslation::class;
    protected $translationForeignKey = 'page_id';
    protected $translatedAttributes  = [
        'slug', 'title', 'content', 'short', 'seo_title', 'seo_description', 'seo_keywords'
    ];

    public $table          = "pages";
    protected $guarded     = [];
    protected $dates       = ['deleted_at', 'archived_at'];
    protected $with        = ['translations'];
    protected $pagebuilder = true;

    public function __construct(array $attributes = [])
    {
        $this->constructWithSnippets();

        parent::__construct($attributes);
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

    public function viewkey(): string
    {
        return $this->morphKey();
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
        return $this->getAllFiles($type)->map->getFileUrl();
    }

    public function mediaUrl($type = null): ?string
    {
        return $this->mediaUrls($type)->first();
    }

    public static function findPublished($id)
    {
        return static::published()->find($id);
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
        if (!$this->slug) {
            return '';
        }
        return route('pages.show', $this->slug);
    }

    public function menuLabel(): string
    {
        return $this->title ?? '';
    }

    public function view()
    {
        $viewPaths = [
            'front.'.$this->morphKey().'.show',
            'front.pages.'.$this->morphKey().'.show',
            'front.pages.show',
            'pages.show',
        ];

        foreach ($viewPaths as $viewPath) {
            if (! view()->exists($viewPath)) {
                continue;
            }

            return view($viewPath, [
                'page' => $this,
            ]);
        }

        throw new NotFoundView('Frontend view could not be determined for page. Make sure to at least provide a default view for pages. This can be either [pages.show] or [front.pages.show].');
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

    public function hasPagebuilder()
    {
        return $this->pagebuilder;
    }
}
