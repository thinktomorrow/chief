<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;
use Thinktomorrow\Chief\Fields\FieldArrangement;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Filters\Filters;
use Thinktomorrow\Chief\Management\Details\HasDetails;
use Thinktomorrow\Chief\Management\Details\HasSections;
use Thinktomorrow\Chief\Management\Exceptions\NonExistingRecord;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;

abstract class AbstractManager
{
    use HasDetails,
        HasSections,
        ManagesMedia,
        ManagesPagebuilder,
        TranslatableCommand,
        AssistedManager;

    protected $queued_translations = [];
    protected $translation_columns = [];

    protected $model;

    /** @var Register */
    protected $registration;

    /** @var string */
    protected $key;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;

        // Upon instantiation, a general model is set that doesn't point to a persisted record.
        $this->manage(app($this->registration->model()));

        // Check if key and model are present since the model should be set by the manager itself
        $this->validateConstraints();
    }

    public function manage($model): Manager
    {
        $this->model = $model;

        return $this;
    }

    public function findManaged($id): Manager
    {
        $model = $this->registration->model();

        $modelInstance = $model::where('id', $id)->withoutGlobalScopes()->first();

        return (new static($this->registration))->manage($modelInstance);
    }

    public function findAllManaged($apply_filters = false): Collection
    {
        $model = $this->registration->model();

        if ($apply_filters) {
            $builder = (new $model)->query();
            $this->filters()->apply($builder);

            $results = $builder->get();
        } else {
            $results = $model::all();
        }

        return $results->map(function ($model) {
            return (new static($this->registration))->manage($model);
        });
    }

    public function model()
    {
        return $this->model;
    }

    /**
     * If the model exists return it otherwise
     * throws a nonExistingRecord exception;
     *
     * @throws NonExistingRecord
     */
    protected function existingModel()
    {
        if (!$this->model ||! $this->model->exists) {
            throw new NonExistingRecord('Model does not exist yet but is expected.');
        }

        return $this->model;
    }

    /**
     * Determine which actions should be available for this
     * manager and their respective routed urls.
     *
     * @param $verb
     * @return null|string
     * @throws NonExistingRecord
     */
    public function route($verb): ?string
    {
        $routes = [
            'index'   => route('chief.back.managers.index', [$this->registration->key()]),
            'create'  => route('chief.back.managers.create', [$this->registration->key()]),
            'store'   => route('chief.back.managers.store', [$this->registration->key()]),
        ];

        if (array_key_exists($verb, $routes)) {
            return $routes[$verb] ?? null;
        }

        //These routes expect the model to be persisted in the database
        $modelRoutes = [
            'edit'    => route('chief.back.managers.edit', [$this->registration->key(), $this->existingModel()->id]),
            'update'  => route('chief.back.managers.update', [$this->registration->key(), $this->existingModel()->id]),
            'delete'  => route('chief.back.managers.delete', [$this->registration->key(), $this->existingModel()->id]),
            'upload'  => route('chief.back.managers.media.upload', [$this->registration->key(), $this->existingModel()->id]),
        ];

        return $modelRoutes[$verb] ?? null;
    }

    public function can($verb): bool
    {
        return !is_null($this->route($verb));
    }

    public function guard($verb): Manager
    {
        if (! $this->can($verb)) {
            NotAllowedManagerRoute::notAllowedVerb($verb, $this);
        }

        return $this;
    }

    /**
     * This determines the arrangement of the manageable fields
     * on the create and edit forms. By default, all fields
     * are presented in their order of appearance
     *
     * @param null $key pinpoint to a specific field arrangement e.g. for create page.
     * @return FieldArrangement
     */
    public function fieldArrangement($key = null): FieldArrangement
    {
        return new FieldArrangement($this->fields());
    }

    public function getFieldValue($field, $default = null)
    {
        // If string is passed, we use this to find the proper field
        if (is_string($field)) {
            foreach ($this->fields()->all() as $possibleField) {
                if ($possibleField->key() == $field) {
                    $field = $possibleField;
                    break;
                }
            }

            if (is_string($field)) {

                // Could be translatable field
                if ($this->isTranslatableKey($field)) {
                    $attribute = substr($field, strrpos($field, '.') + 1);
                    $locale = substr($field, strlen('trans.'), 2);

                    return $this->model->getTranslationFor($attribute, $locale);
                }

                return $default;
            }
        }

        // Is it a media field
        // An array grouped by type is returned. Each media array has an id, filename and path.
        if ($field->ofType(FieldType::MEDIA)) {
            return $this->populateMedia($this->model);
        }

        if ($field->ofType(FieldType::DOCUMENT)) {
            return $this->populateDocuments($this->model);
        }

        return $this->model->{$field->column()};
    }

    private function isTranslatableKey(string $key): bool
    {
        return 0 === strpos($key, 'trans.');
    }

    public function setField(Field $field, Request $request)
    {
        // Is field set as translatable?
        if ($field->isTranslatable()) {
            if (!$this->requestContainsTranslations($request)) {
                return;
            }

            // Make our media fields able to be translatable as well...
            if ($field->ofType(FieldType::MEDIA, FieldType::DOCUMENT)) {
                throw new \Exception('Cannot process the ' . $field->key . ' media field. Currently no support for translatable media files. We should fix this!');
            }

            // Okay so this is a bit odd but since all translations are expected to be inside the trans
            // array, we can add all these translations at once. Just make sure to keep track of the
            // keys since this is what our translation engine requires as well for proper update.
            $this->queued_translations = $request->get('trans');
            $this->translation_columns[] = $field->column();

            return;
        }

        // By default we assume the key matches the attribute / column naming
        $this->model->{$field->column()} = $request->get($field->key());
    }

    public function saveFields(): Manager
    {
        $this->model->save();

        // Translations
        if (!empty($this->queued_translations)) {
            $this->saveTranslations($this->queued_translations, $this->model, $this->translation_columns);
        }

        return (new static($this->registration))->manage($this->model);
    }



    public function delete()
    {
        $this->model->delete();
    }

    public function renderField(Field $field)
    {
        $path = $field->ofType(FieldType::PAGEBUILDER)
            ? 'chief::back._fields.pagebuilder'
            : 'chief::back._fields.formgroup';

        // form element view path
        $viewpath = 'chief::back._fields.' . $field->type;

        return view($path, [
            'field'    => $field,
            'key'      => $field->key, // As parameter so that it can be altered for translatable values
            'manager'  => $this,
            'viewpath' => $viewpath,
        ])->render();
    }

    public static function filters(): Filters
    {
        return new Filters();
    }

    /**
     * This method can be used to manipulate the store request payload
     * before being passed to the storing / updating the models.
     *
     * @param Request $request
     * @return Request
     */
    public function storeRequest(Request $request): Request
    {
        return $request;
    }

    /**
     * This method can be used to manipulate the update request payload
     * before being passed to the storing / updating the models.
     *
     * @param Request $request
     * @return Request
     */
    public function updateRequest(Request $request): Request
    {
        return $request;
    }

    protected function requestContainsTranslations(Request $request): bool
    {
        return $request->has('trans');
    }

    protected function validateConstraints()
    {
        if (!$this->model) {
            throw new \DomainException('Model class should be set for this manager. Please set the model property default via the constructor or by extending the setupDefaults method.');
        }
    }
}
