<?php

namespace Thinktomorrow\Chief\Modules;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Common\Morphable\MorphableContract;
use Thinktomorrow\Chief\Common\Morphable\Morphable;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Relations\PresentForParent;
use Thinktomorrow\Chief\Relations\PresentingForParent;
use Thinktomorrow\Chief\Common\Translatable\Translatable;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Snippets\WithSnippets;

class Module extends Model implements TranslatableContract, HasMedia, ActsAsChild, MorphableContract, PresentForParent
{
    use PresentingForParent {
        presentForParent as presentRawValueForParent;
    }

    use Morphable,
        AssetTrait,
        Translatable,
        BaseTranslatable,
        SoftDeletes,
        ActingAsChild,
        WithSnippets;

    // Explicitly mention the translation model so on inheritance the child class uses the proper default translation model
    protected $translationModel = ModuleTranslation::class;
    protected $translationForeignKey = 'module_id';
    protected $translatedAttributes = [
        'title', 'content'
    ];

    public $useTranslationFallback = true;
    public $table = "modules";
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected $with = ['translations'];

    public function __construct(array $attributes = [])
    {
        $this->constructWithSnippets();

        parent::__construct($attributes);
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function presentForParent(ActsAsParent $parent): string
    {
        $value = $this->presentRawValueForParent($parent);

        if ($this->withSnippets && $this->shouldParseWithSnippets($value)) {
            $value = $this->parseWithSnippets($value);
        }

        return $value;
    }

    /**
     * The page specific ones are the text modules
     * which are added via the page builder
     *
     * @param $query
     */
    public function scopeWithoutPageSpecific($query)
    {
        $query->whereNull('page_id');
    }

    public function isPageSpecific(): bool
    {
        return !is_null($this->page_id);
    }

    /**
     * Each page / Module model can expose some custom fields. Add here the list of fields defined as name => Field where Field
     * is an instance of \Thinktomorrow\Chief\Fields\Types\Field
     *
     * @param null $key
     * @return array
     */
    public function customFields()
    {
        return [];
    }

    /**
     * Each module model can expose the managed translatable fields. These should be included as attributes just like the regular
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
     * The custom addition of fields for a module model.
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
     * The default set of fields for a module model.
     *
     * If you wish to remove any of these fields, you should:
     * 1. override this method with your own and return the comprised list of fields.
     * 2. Provide a migration to remove the column from database and remove the fields from the translatable values of the model.
     *
     * @return array
     */
    public static function defaultTranslatableFields(): array
    {
        return [
            InputField::make('title')->label('titel'),
            HtmlField::make('content')->label('Inhoud'),
        ];
    }

    public function mediaUrls($type = null, $size = 'full'): Collection
    {
        return $this->getAllFiles($type)->map->getFileUrl($size);
    }

    public function mediaUrl($type = null, $size = 'full'): ?string
    {
        return $this->mediaUrls($type, $size)->first();
    }

    public static function mediaFields($key = null)
    {
        $types = [
//            MediaType::BACKGROUND => [
//                'type' => MediaType::BACKGROUND,
//                'label' => 'Achtergrond afbeelding',
//                'description' => '',
//            ]
        ];

        return $key ? array_pluck($types, $key) : $types;
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
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
        return $this->slug ?? '';
    }

    public function flatReferenceGroup(): string
    {
        $classKey = get_class($this);
        $labelSingular = property_exists($this, 'labelSingular') ? $this->labelSingular : str_singular($classKey);

        return $labelSingular;
    }
}
