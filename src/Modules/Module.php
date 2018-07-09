<?php

namespace Thinktomorrow\Chief\Modules;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Collections\ActsAsCollection;
use Thinktomorrow\Chief\Common\Collections\ActingAsCollection;
use Thinktomorrow\Chief\Common\Collections\CollectionDetails;
use Thinktomorrow\Chief\Common\Relations\ActingAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsChild;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Common\Relations\Relation;
use Thinktomorrow\Chief\Common\Translatable\Translatable;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;
use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Traits\AssetTrait;
use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;
use Thinktomorrow\Chief\Common\TranslatableFields\InputField;
use Thinktomorrow\Chief\Media\MediaType;

class Module extends Model implements TranslatableContract, HasMedia, ActsAsChild, ActsAsCollection
{
    use ActingAsCollection,
        AssetTrait,
        Translatable,
        BaseTranslatable,
        SoftDeletes,
        ActingAsChild;

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
     * Details of the collection such as naming, key and class.
     * Used in several dynamic parts of the admin application.
     */
    public function collectionDetails(): CollectionDetails
    {
        $collectionKey = $this->collectionKey();

        return new CollectionDetails(
            $collectionKey,
            static::class,
            $collectionKey ? ucfirst(str_singular($collectionKey)) : null,
            $collectionKey ? ucfirst(str_plural($collectionKey)) : null,
            $this->flatReferenceLabel()
        );
    }

    public function mediaUrls($type = null): Collection
    {
        return $this->getAllFiles($type)->map->getFileUrl();
    }

    public function mediaUrl($type = null): ?string
    {
        return $this->mediaUrls($type)->first();
    }

    public static function mediaFields($key = null)
    {
        $types = [
            MediaType::BACKGROUND => [
                'type' => MediaType::BACKGROUND,
                'label' => 'Achtergrond afbeelding',
                'description' => '',
            ]
        ];

        return $key ? array_pluck($types, $key) : $types;
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    public function presentForParent(ActsAsParent $parent, Relation $relation): string
    {
        $guessedParentViewName = $parent->collectionKey();
        $guessedViewName = $this->collectionKey();
        $viewPaths = ['front.modules.'.$guessedParentViewName.'.'.$guessedViewName, 'front.modules.'.$guessedViewName];

        foreach ($viewPaths as $viewPath) {
            if (! view()->exists($viewPath)) {
                continue;
            }

            return view($viewPath, [
                'banner' => $this,
                'parent' => $parent,
                'relation' => $relation,
            ])->render();
        }

        return '';
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
