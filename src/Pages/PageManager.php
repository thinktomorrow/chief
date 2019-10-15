<?php

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Filters\Filters;
use Thinktomorrow\Chief\Fields\FieldsTab;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Urls\UrlSlugFields;
use Thinktomorrow\Chief\Fields\Types\TextField;
use Thinktomorrow\Chief\Fields\FieldArrangement;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\MediaField;
use Thinktomorrow\Chief\Management\Registration;
use Thinktomorrow\Chief\Fields\RemainingFieldsTab;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\Details\Details;
use Thinktomorrow\Chief\Pages\Application\DeletePage;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Management\Exceptions\DeleteAborted;
use Thinktomorrow\Chief\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\Management\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Management\Assistants\PublishAssistant;
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

    public function __construct(Registration $registration)
    {
        parent::__construct($registration);
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
        return parent::fields()->add(
            $this->pageBuilderField(),
            InputField::make('title')->translatable($this->model->availableLocales())
                                     ->validation('required-fallback-locale|max:200', [], [
                                         'trans.'.config('app.fallback_locale', 'nl').'.title' => 'title',
                                     ])
                                     ->label('De titel van je '.$this->model->labelSingular ?? 'pagina')
                                     ->description('Dit is de titel die zal worden getoond in de overzichten en modules.'),
            InputField::make('seo_title')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine titel'),
            TextField::make('seo_description')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine omschrijving')
                ->description('omschrijving van de pagina zoals in search engines (o.a. google) wordt weergegeven.'),
            InputField::make('seo_keywords')
                ->validation('max:250')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine sleutelwoorden')
                ->description('sleutelwoorden van de pagina waarop in search engines (o.a google) gezocht kan worden.'),
            MediaField::make('seo_image')
                ->translatable($this->model->availableLocales())
                ->label('Zoekmachine foto')
                ->description('foto die bij het delen van deze pagina getoont word. (afmeting: 1200x627px)')
        );
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
            return new FieldArrangement($this->fieldsWithAssistantFields()->filterBy(function ($field) {
                return in_array($field->key, ['title']);
            }));
        }

        $tabs = [
            new FieldsTab('pagina', ['sections']),
            new RemainingFieldsTab('algemeen'),
            new FieldsTab('url', ['url-slugs'], 'chief::back.pages._partials.url', [
                'redirects' =>  UrlSlugFields::redirectsFromModel($this->model),
            ]),
            new FieldsTab('seo', ['seo_title', 'seo_description', 'seo_keywords', 'seo_image']),
        ];

        if (Module::atLeastOneRegistered()) {
            array_splice($tabs, 1, 0, [new FieldsTab('modules', [], 'chief::back.pages._partials.modules')]);
        }

        return new FieldArrangement($this->fieldsWithAssistantFields(), $tabs);
    }

    public function details(): Details
    {
        // For existing model
        if ($this->model->id) {
            return parent::details()
                ->set('title', ucfirst($this->model->title))
                ->set('intro', 'Aangepast ' . $this->model->updated_at->format('d/m/Y H:i'))
                ->set('context', '<span class="inline-xs stack-s">' . $this->assistant('publish')->publicationStatusAsLabel() . '</span>');
        }

        return parent::details();
    }

    public function saveFields(Request $request)
    {
        // Store the morph_key upon creation
        if ($this->model instanceof MorphableContract && ! $this->model->morph_key) {
            $this->model->morph_key = $this->model->morphKey();
        }

        parent::saveFields($request);
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
        $urls = $request->get('url-slugs', []);

        foreach ($request->get('trans', []) as $locale => $translation) {
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
        foreach ($request->get('trans', []) as $locale => $translation) {
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
