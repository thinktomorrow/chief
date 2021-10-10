<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Support\Facades\View;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Actions\DeleteModel;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;
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

        if (! $model || ! $model instanceof WithPageState) {
            return true;
        }

        // Model cannot be in deleted state for editing purposes.
        if (in_array($action, ['edit', 'update'])) {
            return ! ($model->getPageState() == PageState::DELETED);
        }

        return PageState::make($model)->can($action);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->guard('index');

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

        $model = $this->handleStore($request);

        return redirect()->to($this->route('edit', $model))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $model->adminConfig()->getPageTitle() . '" is toegevoegd');
    }

    private function handleStore(Request $request)
    {
        /** @var ManagedModel $model */
        $model = new $this->managedModelClass();

        $fields = Fields::make($model->fields())->notTagged(['edit', 'not-on-create']);

        $this->fieldValidator()->handle($fields, $request->all());

        // TODO: extract all uploadedFile instances from the input...
        $model->saveFields($fields, $request->all(), $request->allFiles());

        event(new ManagedModelCreated($model->modelReference()));

        return $model;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        $this->guard('edit', $model);

        $fields = Fields::make($model->fields())->model($model);

        View::share('model', $model);
        View::share('manager', $this);

        return view('chief::manager.edit', [
            'manager' => $this,
            'model' => $model,
            'fieldWindows' => $fields->allWindows(),
            'fields' => $fields,
        ]);
    }

    public function update(Request $request, $id)
    {
        $model = $this->handleUpdate($request, $id);

        return redirect()->to($this->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  <a href="' . $this->route('edit', $model) . '">' . $model->adminConfig()->getPageTitle() . '</a> is aangepast');
    }

    private function handleUpdate(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        $this->guard('update', $model);

        $fields = Fields::make($model->fields());

        $this->fieldValidator()->handle($fields, $request->all());

        $model->saveFields($fields, $request->all(), $request->allFiles());

        event(new ManagedModelUpdated($model->modelReference()));

        return $model;
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
