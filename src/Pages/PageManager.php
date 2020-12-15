<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Fragments\Fragments;
use Thinktomorrow\Chief\Fields\Types\ImageField;
use Thinktomorrow\Chief\States\PageStatePresenter;
use Thinktomorrow\Chief\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Filters\Filters;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Fields\Types\TextField;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Management\Assistants\PublishAssistant;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Management\Details\Details;
use Thinktomorrow\Chief\Pages\Application\DeletePage;
use Thinktomorrow\Chief\Management\Exceptions\DeleteAborted;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;

class PageManager extends AbstractManager implements Manager
{
    /** @var PageBuilderField */
    private $pageBuilderField;

    protected $assistants = [
        'url'     => UrlAssistant::class,
        'archive' => ArchiveAssistant::class,
        'publish' => PublishAssistant::class,
    ];

    public function can($verb): bool
    {
        try {
            $this->authorize($verb);

            return parent::can($verb);
        } catch (NotAllowedManagerRoute $e) {
            return false;
        }
    }

    /**
     * @param $verb
     * @throws NotAllowedManagerRoute
     */
    private function authorize($verb)
    {
        $permission = 'update-page';

        if (in_array($verb, ['index', 'show'])) {
            $permission = 'view-page';
        } elseif (in_array($verb, ['create', 'store'])) {
            $permission = 'create-page';
        } elseif (in_array($verb, ['delete'])) {
            $permission = 'delete-page';
        }

        if (!auth()->guard('chief')->user()->hasPermissionTo($permission)) {
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
        return parent::fields()->add(
            $this->pageBuilderField(),
            InputField::make('title')->translatable($this->model->availableLocales())
                ->validation('required-fallback-locale|max:200', [], [
                    'trans.' . config('app.fallback_locale', 'nl') . '.title' => 'title',
                ])
                ->label('Intern label')
                ->description('Dit is de benaming die zal worden getoond in de admin selectie velden.')
                ->tag('general'),
            InputField::make('seo_title')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine titel')
                ->description('Maximum 66 tekens voor een optimale weergave in Google.')
                ->characterCount(66)
                ->tag('seo'),
            TextField::make('seo_description')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine omschrijving')
                ->description('Omschrijving van de pagina zoals in search engines (o.a. google) wordt weergegeven. Maximum 160 tekens voor een optimale weergave in Google.')
                ->characterCount(160)
                ->tag('seo'),
            InputField::make('seo_keywords')
                ->validation('max:250')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine sleutelwoorden')
                ->description('Sleutelwoorden van de pagina waarop in search engines (o.a google) gezocht kan worden.')
                ->tag('seo'),
            ImageField::make('seo_image')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine foto')
                ->description('Foto die bij het delen van deze pagina getoond wordt. De ideale afmetingen zijn 1200px breed op 627px hoog.')
                ->tag('seo')
        );
    }

    public static function filters(): Filters
    {
        return new Filters([
            PublishedFilter::class,
        ]);
    }

    private function pageBuilderField()
    {
        if ($this->pageBuilderField) {
            return $this->pageBuilderField;
        }

        return $this->pageBuilderField = $this->createPagebuilderField();
    }

    public function createView(): string
    {
        return 'chief::back.pages._partials._createform';
    }

    public function editView(): string
    {
        return 'chief::back.pages._partials._editform';
    }

    public function createFields(): Fields
    {
        return $this->fields()->keyed('title');
    }

    public function saveCreateFields(Request $request): void
    {
        // Store the morph_key upon creation
        if ($this->model instanceof MorphableContract && !$this->model->morph_key) {
            $this->model->morph_key = $this->model->morphKey();
        }

        if($this->isManualSortable() && !$request->has('order')) {
            $this->model->order = $this->modelInstance()::orderBy('order', 'desc')->first()->order + 1;
        }

        parent::saveFields($request, $this->createFields()->merge($this->fieldsWithAssistantFields()->keyed('url-slugs')));
    }

    public function details(): Details
    {
        // For existing model
        if ($this->hasExistingModel()) {
            return parent::details()
                ->set('title', $this->existingModel()->title ? ucfirst($this->existingModel()->title) : '')
                ->set('intro', PageStatePresenter::fromModel($this->existingModel())->label())
                ->set('context', '');
        }

        return parent::details();
    }

    public function delete()
    {
        $this->guard('delete');

        if (request()->get('deleteconfirmation') !== 'DELETE') {
            throw new DeleteAborted();
        }

        app(DeletePage::class)->handle($this->model->flatreference());
    }

    public function storeRequest(Request $request): Request
    {
        $trans = [];
        $urls = $request->input('url-slugs', []);

        foreach ($request->input('trans', []) as $locale => $translation) {
            if (is_array_empty($translation)) {
                continue;
            }

            $trans[$locale] = $this->addDefaultShortDescription($translation);

            // Automatically add an url for this locale based on the given title
            if (!isset($urls[$locale]) && isset($translation['title'])) {
                $urls[$locale] = Str::slug($translation['title']);
            }
        }

        // Merge with request...
        return $request->merge(['trans' => $trans, 'url-slugs' => $urls]);
    }

    public function updateRequest(Request $request): Request
    {
        $trans = [];
        foreach ($request->input('trans', []) as $locale => $translation) {
            if (is_array_empty($translation)) {

                // Nullify all values
                $trans[$locale] = array_map(function ($value) {
                    return null;
                }, $translation);
                continue;
            }

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
