<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Admin\Users\VisitedUrl;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\DiscoverTraitMethods;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedNode;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

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
        ];
    }

    public function canCrudAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['index', 'create', 'store', 'edit', 'update'])) {
            return false;
        }

        try {
            $permission = 'update-page';

            if (in_array($action, ['index', 'show'])) {
                $permission = 'view-page';
            } elseif (in_array($action, ['create', 'store'])) {
                $permission = 'create-page';
            }

            $this->authorize($permission);
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        if (in_array($action, ['index', 'create', 'store'])) {
            return true;
        }

        // Model cannot be in deleted state for editing purposes.
        if ($model && $model instanceof StatefulContract && in_array($action, ['edit', 'update'])) {
            return ! ($model->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY) == PageState::deleted);
        }

        return true;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $this->guard('index');

        app(VisitedUrl::class)->add(request()->fullUrl());

        View::share('manager', $this);
        View::share('resource', $this->resource);
        View::share('model', $model = $this->managedModelClassInstance());

        if ($model instanceof Nestable) {
            $rootId = $request->input('root_id', null);

            $filteredModelIds = $this->indexModelIds();
            $filteredTree = $this
                ->getTree($rootId)
                ->shake(fn ($node) => in_array($node->getModel()->getKey(), $filteredModelIds));

            View::share('tree', $filteredTree);
            View::share('root', $this->getRoot($rootId));
        } else {
            View::share('models', $this->indexModels());
        }

        return $this->resource->getIndexView();
    }

    private function getTree(?string $rootId = null): iterable
    {
        /** @var NestedTree $tree */
        $tree = $this->resource->nestableRepository()->getTree();

        return $rootId
            ? $tree->find(fn (NestedNode $node) => $node->getId() == $rootId)->getChildNodes()
            : $tree;
    }

    private function getRoot(?string $rootId = null): ?NestedNode
    {
        if (! $rootId) {
            return null;
        }

        return $this->resource->nestableRepository()->getTree()->find(fn (NestedNode $node) => $node->getId() == $rootId);
    }

    protected function indexModels(): Paginator
    {
        // Apply filtering - this also includes default sorting
        $this->filters()->apply($builder = $this->managedModelClass()::query());

        if ($this->managedModelClassInstance() instanceof Visitable) {
            $builder->with(['urls']);
        }

        if (! $pagination = $this->resource->getIndexPagination()) {
            return $builder->get();
        }

        return $builder->paginate($pagination)->onEachSide(1)->withQueryString();
    }

    protected function indexModelIds(): array
    {
        $this->filters()->apply($builder = $this->managedModelClass()::query());

        $idColumn = $this->managedModelClassInstance()->getKeyName();

        return $builder
            ->select($idColumn)
            ->get()
            ->pluck($idColumn)
            ->toArray();
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

        if (public_method_exists($this->resource, 'filters')) {
            $filters = $filters->merge(Filters::make($this->resource::filters()));
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
    public function create(Request $request)
    {
        $model = $this->managedModelClassInstance($this->resource->getInstanceAttributes($request));

        View::share('manager', $this);
        View::share('model', $model);
        View::share('resource', $this->resource);

        View::share('forms', Forms::make($this->resource->fields($model))
            ->fillModel($model)
            ->fillFields($this, $model));

        return $this->resource->getCreatePageView();
    }

    public function store(Request $request)
    {
        $this->guard('store');

        $model = $this->handleStore($request);

        $redirectAfterCreate = $this->resource->getRedirectAfterCreate($model);

        if ($request->expectsJson()) {
            return response()->json([
                'redirect_to' => $redirectAfterCreate,
            ]);
        }

        return redirect()->to($redirectAfterCreate);
    }

    private function handleStore(Request $request)
    {
        $model = $this->managedModelClassInstance($this->resource->getInstanceAttributes($request));

        $fields = Forms::make($this->resource->fields($model))
            ->fillModel($model)
            ->getFields()
            ->notTagged(['edit', 'not-on-create']);

        $this->fieldValidator()->handle($fields, $request->all());

        // TODO: extract all uploadedFile instances from the input...
        app($this->resource->getSaveFieldsClass())->save($model, $fields, $request->all(), $request->allFiles());

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

        // TODO/ remove share??
        View::share('manager', $this);
        View::share('model', $model);
        View::share('resource', $this->resource);
        View::share('forms', Forms::make($this->resource->fields($model))->fill($this, $model));

        $stateConfigs = [];

        if ($model instanceof StatefulContract) {
            $stateConfigs = collect($model->getStateKeys())
                ->map(fn (string $stateKey) => $model->getStateConfig($stateKey))
                ->filter(fn ($stateConfig) => $stateConfig instanceof StateAdminConfig)
                ->all();
        }

        View::share('stateConfigs', $stateConfigs);

        return $this->resource->getPageView();
    }

    public function update(Request $request, $id)
    {
        $model = $this->handleUpdate($request, $id);

        return redirect()->to($this->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  <a href="' . $this->route('edit', $model) . '">' . $this->resource->getPageTitle($model) . '</a> is aangepast');
    }

    private function handleUpdate(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        $this->guard('update', $model);

        $fields = Forms::make($this->resource->fields($model))
            ->fillModel($model)
            ->getFields();

        $this->fieldValidator()->handle($fields, $request->all());

        app($this->resource->getSaveFieldsClass())->save($model, $fields, $request->all(), $request->allFiles());

        event(new ManagedModelUpdated($model->modelReference()));

        return $model;
    }
}
