<?php

namespace Thinktomorrow\Chief\Modules;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Collections\ActsAsCollection;
use Thinktomorrow\Chief\Common\Collections\ActingAsCollection;
use Thinktomorrow\Chief\Common\Collections\CollectionKeys;
use Thinktomorrow\Chief\Common\Relations\ActingAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Common\Relations\PresentForParent;
use Thinktomorrow\Chief\Common\Relations\PresentingForParent;
use Thinktomorrow\Chief\Common\Translatable\Translatable;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;
use Thinktomorrow\Chief\Common\TranslatableFields\InputField;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Snippets\WithSnippets;

class Module extends Model implements TranslatableContract, HasMedia, ActsAsChild, ActsAsCollection, PresentForParent
{
    use PresentingForParent {
        presentForParent as presentRawValueForParent;
    }

    use ActingAsCollection,
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

    public $table = "modules";
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected $with = ['translations'];

    public function __construct(array $attributes = [])
    {
        $this->translatedAttributes = array_merge($this->translatedAttributes, array_keys(static::translatableFields()));

        $this->withSnippets = config('thinktomorrow.chief.withSnippets', false);

        parent::__construct($attributes);
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function presentForParent(ActsAsParent $parent): string
    {
        $value = $this->presentRawValueForParent($parent);

        if($this->withSnippets && $this->shouldParseWithSnippets($value)) {
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
     * is an instance of \Thinktomorrow\Chief\Common\TranslatableFields\Field
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
            'title' => InputField::make()->label('titel'),
            'content' => HtmlField::make()->label('Inhoud'),
        ];
    }

    /**
     * We exclude the generic textModule out of the available collections.
     * @return Collection
     */
    public static function availableCollections(): Collection
    {
        return CollectionKeys::fromConfig()
            ->filterByType(static::collectionType())
            ->rejectByClass(TextModule::class)
            ->rejectByClass(PagetitleModule::class)
            ->toCollectionDetails();
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

    public function flatReferenceLabel(): string
    {
        return $this->slug ?? '';
    }

    public function flatReferenceGroup(): string
    {
        return $this->collectionDetails()->singular;
    }
}
