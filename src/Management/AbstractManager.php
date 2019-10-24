<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Concerns\Translatable\TranslatableCommand;
use Thinktomorrow\Chief\Fields\FieldArrangement;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\RenderingFields;
use Thinktomorrow\Chief\Fields\SavingFields;
use Thinktomorrow\Chief\Filters\Filters;
use Thinktomorrow\Chief\Management\Assistants\AssistedManager;
use Thinktomorrow\Chief\Management\Details\HasDetails;
use Thinktomorrow\Chief\Management\Details\HasSections;
use Thinktomorrow\Chief\Management\Exceptions\NonExistingRecord;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;

abstract class AbstractManager
{
    use RenderingFields,
        SavingFields,
        HasDetails,
        HasSections,
        ManagesMedia,
        ManagesPagebuilder,
        TranslatableCommand,
        AssistedManager;

    protected $translation_columns = [];

    protected $model;

    /** @var Register */
    protected $registration;

    protected $pageCount = 20;
    protected $paginated = true;

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

    public function indexCollection()
    {
        $model = $this->registration->model();

        $builder = (new $model)->query();

        $this->filters()->apply($builder);

        $builder = $this->indexBuilder($builder);

        $builder = $this->indexSorting($builder);
        
        if ($this->paginated) {
            return $this->indexPagination($builder);
        }
        
        return $builder->get()->map(function ($model) {
            return (new static($this->registration))->manage($model);
        });
    }

    protected function indexBuilder(Builder $builder): Builder
    {
        return $builder;
    }

    protected function indexSorting(Builder $builder): Builder
    {
        if ($this->isAssistedBy('publish')) {
            $builder->orderBy('published', 'DESC');
        }

        // if model has no timestamps, updated_at doesn't exist
        if ($this->model()->timestamps) {
            $builder->orderBy('updated_at', 'DESC');
        }

        return $builder;
    }

    protected function indexPagination($builder): Paginator
    {
        $paginator = $builder->paginate($this->pageCount);

        $modifiedCollection = $builder->paginate($this->pageCount)->getCollection()->transform(function ($model) {
            return (new static($this->registration))->manage($model);
        });

        return $paginator->setCollection($modifiedCollection);
    }

    public function model()
    {
        return $this->model;
    }

    public function hasExistingModel(): bool
    {
        return ($this->model && $this->model->exists);
    }

    /**
     * If the model exists return it otherwise
     * throws a nonExistingRecord exception;
     *
     * @throws NonExistingRecord
     */
    protected function existingModel()
    {
        if (!$this->hasExistingModel()) {
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

    public function fields(): Fields
    {
        return new Fields();
    }

    /**
     * Enrich the manager fields with any of the assistant specified fields
     *
     * @return Fields
     * @throws \Exception
     */
    public function fieldsWithAssistantFields(): Fields
    {
        $fields = $this->fields();

        foreach ($this->assistants() as $assistant) {
            if (! method_exists($assistant, 'fields')) {
                continue;
            }

            $fields = $fields->merge($assistant->fields());
        }

        return $fields;
    }

    /**
     * This determines the arrangement of the manageable fields
     * on the create and edit forms. By default, all fields
     * are presented in their order of appearance
     *
     * @param null $key pinpoint to a specific field arrangement e.g. for create page.
     * @return FieldArrangement
     * @throws \Exception
     */
    public function fieldArrangement($key = null): FieldArrangement
    {
        return new FieldArrangement($this->fieldsWithAssistantFields());
    }

    public function delete()
    {
        $this->model->delete();
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
