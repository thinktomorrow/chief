<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Application\DeleteModel;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\DiscoverTraitMethods;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait CrudAssistant
{
    abstract protected function fieldsModel($id);
    abstract protected function fieldValidator(): FieldValidator;
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;
    abstract protected function guard(string $action, $model = null);

    public function routesCrudAssistant(): array
    {
        return [
            ManagedRoute::get('index', ''),
            ManagedRoute::get('edit', '{id}/edit'),
            ManagedRoute::get('create'),
            ManagedRoute::post('store'),
            ManagedRoute::put('update', '{id}/update'),
            ManagedRoute::delete('delete', '{id}'),
        ];
    }

    public function canCrudAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['index', 'create', 'store', 'edit', 'update', 'delete'])) {
            return false;
        }

        try {
            $permission = 'update-page';

            if (in_array($action, ['index', 'show'])) {
                $permission = 'view-page';
            } elseif (in_array($action, ['create', 'store'])) {
                $permission = 'create-page';
            } elseif (in_array($action, ['delete'])) {
                $permission = 'delete-page';
            }

            $this->authorize($permission);
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        if (in_array($action, ['index', 'create', 'store'])) {
            return true;
        }

        if (! $model || ! $model instanceof StatefulContract) {
            return true;
        }

        // Model cannot be in deleted state for editing purposes.
        if (in_array($action, ['edit', 'update'])) {
            return ! ($model->stateOf(PageState::KEY) == PageState::DELETED);
        }

        return PageState::make($model)->can($action);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $modelClass = $this->managedModelClass();

        return view('chief::manager.index', [
            'manager' => $this,
            'model' => new $modelClass(),
            'models' => $this->indexModels(),
        ]);
    }

    protected function indexModels(): Paginator
    {
        $this->filters()->apply($builder = $this->managedModelClass()::query());

        $pagination = (new $this->managedModelClass())->adminConfig()->getPagination();

        if (! $pagination) {
            return $builder->get();
        }

        return $builder->paginate($pagination);
    }

    public function filters(): Filters
    {
        return $this->defaultFilters();
    }

    private function defaultFilters(): Filters
    {
        $filters = new Filters();

        foreach (DiscoverTraitMethods::belongingTo(static::class, 'filters') as $method) {
            $filters = $filters->merge($this->$method());
        }

        $filters = $this->addUpdatedFilter($filters);

        if (public_method_exists($this->managedModelClass(), 'filters')) {
            $filters = $filters->merge(Filters::make($this->managedModelClass()::filters()));
        }

        return $filters;
    }

    private function addUpdatedFilter(Filters $filters): Filters
    {
        $modelClass = $this->managedModelClass();

        // if model has no timestamps, updated_at doesn't exist
        if ((new $modelClass)->timestamps) {
            return $filters->add(HiddenFilter::make('updated', function ($query) {
                return $query->orderBy('updated_at', 'DESC');
            }));
        }

        return $filters;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $modelClass = $this->managedModelClass();
        $model = new $modelClass();

        return view('chief::manager.create', [
            'manager' => $this,
            'model' => $model,
            'fields' => Fields::make($model->fields())->notTagged(['edit', 'not-on-create']),
        ]);
    }

    public function store(Request $request)
    {
        $this->guard('store');

        $modelClass = $this->managedModelClass();

        /** @var ManagedModel $model */
        $model = new $modelClass();

        $fields = Fields::make($model->fields())->notTagged(['edit', 'not-on-create']);
        $this->fieldValidator()->handle($fields, $request->all());

        // TODO: extract all uploadedFile instances from the input...
        $model->saveFields($fields, $request->all(), $request->allFiles());

        return redirect()->to($this->route('edit', $model))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $model->adminConfig('pageTitle') . '" is toegevoegd');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        $this->guard('edit', $model);

        return view('chief::manager.edit', [
            'manager' => $this,
            'model' => $model,
            'fields' => Fields::make($model->fields())->model($model),
        ]);
    }

    public function update(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        $this->guard('update', $model);

        $this->fieldValidator()->handle(Fields::make($model->fields()), $request->all());

        $model->saveFields(Fields::make($model->fields()), $request->all(), $request->allFiles());

        return redirect()->to($this->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  <a href="' . $this->route('edit', $model) . '">' . $model->adminConfig()->getPageTitle() . '</a> is aangepast');
    }

    public function delete(Request $request, $id)
    {
        $model = $this->managedModelClass()::withoutGlobalScopes()->findOrFail($id);

        $this->guard('delete', $model);

        if ($request->get('deleteconfirmation') !== 'DELETE') {
            return redirect()->back()->with('messages.warning', $model->adminConfig()->getPageTitle() . ' is niet verwijderd.');
        }

        app(DeleteModel::class)->handle($model);

        return redirect()->to($this->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $model->adminConfig()->getPageTitle() . '" is verwijderd.');
    }
}
