<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Concerns\Sluggable\UniqueSlug;
use Thinktomorrow\Chief\Fields\FieldArrangement;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\FieldTab;
use Thinktomorrow\Chief\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\MediaField;
use Thinktomorrow\Chief\Fields\Types\TextField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Management\Details\Details;
use Thinktomorrow\Chief\Management\Exceptions\DeleteAborted;
use Thinktomorrow\Chief\Management\ManagerThatPreviews;
use Thinktomorrow\Chief\Management\ManagerThatPublishes;
use Thinktomorrow\Chief\Management\ManagesPreviews;
use Thinktomorrow\Chief\Management\ManagesPublishing;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Management\Registration;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\Application\ArchivePage;
use Thinktomorrow\Chief\Pages\Application\DeletePage;

class PageManager extends AbstractManager implements Manager, ManagerThatPublishes, ManagerThatPreviews
{
    use ManagesPublishing,
        ManagesPreviews;

    /** @var \Thinktomorrow\Chief\Concerns\Sluggable\UniqueSlug */
    private $uniqueSlug;

    /** @var PageBuilderField */
    private $pageBuilderField;

    public function __construct(Registration $registration)
    {
        parent::__construct($registration);

        $this->uniqueSlug = UniqueSlug::make(new PageTranslation)->slugResolver(function ($value) {
            return str_slug_slashed($value);
        });
    }

    public function can($verb): bool
    {
        $this->authorize($verb);

        return parent::can($verb);
    }

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
            throw NotAllowedManagerRoute::notAllowedPermission($permission);
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
                                     ->validation('required-fallback-locale|max:200')
                                     ->label('Pagina titel')
                                     ->description('Titel die kan worden getoond in de overzichten en modules. De titel op de pagina zelf wordt beheerd via de pagina tab'),
            InputField::make('slug')->translatable($this->model->availableLocales())
                ->validation($this->model->id
                    ? 'unique:page_translations,slug,' . $this->model->id . ',page_id'
                    : 'unique:page_translations,slug'
                ),
            TextField::make('short')->translatable($this->model->availableLocales()),
            HtmlField::make('content')->translatable($this->model->availableLocales()),
            InputField::make('seo_title')->translatable($this->model->availableLocales()),
            TextField::make('seo_description')->translatable($this->model->availableLocales()),
            MediaField::make(MediaType::HERO)->multiple(false),
            MediaField::make(MediaType::THUMB)->multiple(false),
        ]);
    }

    private function pageBuilderField()
    {
        if($this->pageBuilderField) return $this->pageBuilderField;

        return $this->pageBuilderField = $this->createPagebuilderField();
    }

    public function fieldArrangement(): FieldArrangement
    {
        return new FieldArrangement($this->fields(), [
            new FieldTab('pagina', ['sections']),
            new FieldTab('inhoud', ['title', 'content', MediaType::HERO, MediaType::THUMB]),
            new FieldTab('eigen modules', [], 'chief::back.pages._partials.modules'),
            new FieldTab('seo', ['seo_title', 'seo_content']),
        ]);
    }

    public function modelDetails(): Details
    {
        // For existing model
        if($this->model->id) {
            return parent::details()
                ->set('title', $this->model->title)
                ->set('intro', 'laatst aangepast op ' . $this->model->updated_at->format('d/m/Y H:i'))
                ->set('context', '<span class="inline-s">' . $this->publicationStatusAsLabel() . '</span>');
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

    public function archive()
    {
        app(ArchivePage::class)->handle($this->model->id);
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
