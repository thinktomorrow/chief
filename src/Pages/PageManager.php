<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Concerns\Sluggable\UniqueSlug;
use Thinktomorrow\Chief\Fields\FieldArrangement;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\FieldsTab;
use Thinktomorrow\Chief\Fields\RemainingFieldsTab;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\TextField;
use Thinktomorrow\Chief\Filters\Filters;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Management\Assistants\PublishAssistant;
use Thinktomorrow\Chief\Management\Details\Details;
use Thinktomorrow\Chief\Management\Exceptions\DeleteAborted;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Registration;
use Thinktomorrow\Chief\Pages\Application\DeletePage;

class PageManager extends AbstractManager implements Manager
{
    /** @var \Thinktomorrow\Chief\Concerns\Sluggable\UniqueSlug */
    private $uniqueSlug;

    /** @var PageBuilderField */
    private $pageBuilderField;

    protected $assistants = [
        'archive' => ArchiveAssistant::class,
        'publish' => PublishAssistant::class,
    ];

    public function __construct(Registration $registration)
    {
        parent::__construct($registration);

        $this->uniqueSlug = UniqueSlug::make(new PageTranslation)->slugResolver(function ($value) {
            return str_slug_slashed($value);
        });
    }

    public function can($verb): bool
    {
        try {
            $this->authorize($verb);
        } catch (NotAllowedManagerRoute $e) {
            return false;
        }

        return parent::can($verb);
    }

    /**
     * @param $verb
     * @throws NotAllowedManagerRoute
     */
    private function authorize($verb)
    {
        $permission = 'update-page';

        if (in_array($verb, ['index','show'])) {
            $permission = 'view-page';
        } elseif (in_array($verb, ['create','store'])) {
            $permission = 'create-page';
        } elseif (in_array($verb, ['delete'])) {
            $permission = 'delete-page';
        }

        if (! auth()->guard('chief')->user()->hasPermissionTo($permission)) {
            throw NotAllowedManagerRoute::notAllowedPermission($permission, $this);
        }
    }

    /**
     * The set of fields that should be manageable for a certain model.
     *
     * Additionally, you should:
     * 1. Make sure to setup the proper migrations and
     * 2. For a translatable field you should add this field to the $translatedAttributes property of the model as well.
     *
     * @return Fields
     */
    public function fields(): Fields
    {
        return new Fields([
            $this->pageBuilderField(),
            InputField::make('title')->translatable($this->model->availableLocales())
                                     ->validation('required-fallback-locale|max:200', [], [
                                         'trans.'.config('app.fallback_locale', 'nl').'.title' => 'title',
                                     ])
                                     ->label('De titel van je '.$this->model->labelSingular ?? 'pagina')
                                     ->description('Dit is de titel die zal worden getoond in de overzichten en modules.<br> Deze zal gebruikt worden als interne titel en slug van de nieuwe pagina.'),
            InputField::make('slug')
                ->translatable($this->model->availableLocales())
                ->validation($this->model->id
                    ? 'required-fallback-locale|unique:page_translations,slug,' . $this->model->id . ',page_id'
                    : 'required-fallback-locale|unique:page_translations,slug', [], [
                    'trans.'.config('app.fallback_locale', 'nl').'.slug' => 'slug'
                ])
                ->label('Link')
                ->description('De unieke url verwijzing naar deze pagina.')
                ->prepend(collect($this->model->availableLocales())->mapWithKeys(function ($locale) {
                    return [$locale => url($this->model->baseUrlSegment($locale)).'/'];
                })->all()),
            InputField::make('seo_title')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine titel'),
            TextField::make('seo_description')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine omschrijving')
                ->description('omschrijving van de pagina zoals in search engines (o.a. google) wordt weergegeven.'),
            InputField::make('seo_keywords')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine sleutelwoorden')
                ->description('sleutelwoorden van de pagina waarop in search engines (o.a google) gezocht kan worden.'),
        ]);
    }

    public static function filters(): Filters
    {
        return new Filters([
            PublishedFilter::class
        ]);
    }

    private function pageBuilderField()
    {
        if ($this->pageBuilderField) {
            return $this->pageBuilderField;
        }

        return $this->pageBuilderField = $this->createPagebuilderField();
    }

    public function fieldArrangement($key = null): FieldArrangement
    {
        if ($key == 'create') {
            return new FieldArrangement($this->fields()->filterBy(function ($field) {
                return $field->key == 'title';
            }));
        }

        return new FieldArrangement($this->fields(), [
            new FieldsTab('pagina', ['sections']),
            new RemainingFieldsTab('inhoud'),
            new FieldsTab('eigen modules', [], 'chief::back.pages._partials.modules'),
            new FieldsTab('seo', ['seo_title', 'seo_description', 'seo_keywords']),
        ]);
    }

    public function details(): Details
    {
        // For existing model
        if ($this->model->id) {
            return parent::details()
                ->set('title', $this->model->title)
                ->set('intro', 'laatst aangepast op ' . $this->model->updated_at->format('d/m/Y H:i'))
                ->set('context', '<span>' . $this->assistant('publish')->publicationStatusAsLabel() . '</span>');
        }

        return parent::details();
    }

    public function saveFields(): Manager
    {
        // Store the morph_key upon creation
        if (! $this->model->morph_key) {
            $this->model->morph_key = $this->model->morphKey();
        }

        return parent::saveFields();
    }

    public function delete()
    {
        if (request()->get('deleteconfirmation') !== 'DELETE') {
            throw new DeleteAborted();
        }

        app(DeletePage::class)->handle($this->model->id);
    }

    public function storeRequest(Request $request): Request
    {
        $trans = [];
        foreach ($request->get('trans', []) as $locale => $translation) {
            if (is_array_empty($translation)) {
                continue;
            }

            $translation = $this->enforceUniqueSlug($request->get('trans'), $locale, $this->model);
            $trans[$locale] = $this->addDefaultShortDescription($translation);
        }

        // Merge with request...
        return $request->merge(['trans' => $trans]);
    }

    public function updateRequest(Request $request): Request
    {
        $trans = [];
        foreach ($request->get('trans', []) as $locale => $translation) {
            if (is_array_empty($translation)) {

                // Nullify all values
                $trans[$locale] = array_map(function ($value) {
                    return null;
                }, $translation);
                continue;
            }

            $translation = $this->enforceUniqueSlug($request->get('trans'), $locale, $this->model);
            $trans[$locale] = $this->addDefaultShortDescription($translation);
        }

        // Merge with request...
        return $request->merge(['trans' => $trans]);
    }

    public function afterStore($request)
    {
        Audit::activity()
            ->performedOn($this->model)
            ->log('created');
    }

    public function afterUpdate($request)
    {
        Audit::activity()
            ->performedOn($this->model)
            ->log('edited');
    }

    private function enforceUniqueSlug(array $translations, string $locale, Page $page): array
    {
        $translation = $translations[$locale];

        $translation['slug']    = $translation['slug'] ?? $translation['title'];
        $translation['slug']    = $this->uniqueSlug->get($translation['slug'], $page->getTranslation($locale));

        return $translation;
    }

    /**
     * @param array $translation
     * @return array
     */
    private function addDefaultShortDescription(array $translation): array
    {
        if (isset($translation['content'])) {
            $translation['short'] = $translation['short'] ?? teaser($translation['content'], 100);
        }

        return $translation;
    }
}
