<?php

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Admin\Users\VisitedUrl;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\Managers\DiscoverTraitMethods;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedNode;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferencePresenter;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait IndexAssistant
{
    abstract protected function fieldsModel($id);

    abstract protected function fieldValidator(): FieldValidator;

    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    abstract protected function guard(string $action, $model = null);

    public function routesIndexAssistant(): array
    {
        return [
            ManagedRoute::get('index', ''),
        ];
    }

    public function canIndexAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['index'])) {
            return false;
        }

        try {
            $this->authorize('view-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        if (in_array($action, ['index'])) {
            return true;
        }

        return true;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return $this->resource->getIndexView();

        $this->guard('index');

        app(VisitedUrl::class)->add(request()->fullUrl());

        View::share('manager', $this);
        View::share('resource', $this->resource);
        View::share('model', $model = $this->managedModelClassInstance());


        if ($model instanceof Nestable) {
            // TODO: this should be changed to the repository pattern like:
            // app($resource->indexRepository(), ['resourceKey' => $resourceKey])->applyFilters(request()->all())->getNestableResults()
            // indexModelIds can then be removed
            $filteredModelIds = $this->indexModelIds();

            $filteredTree = $this
                ->getTree()
                ->shake(fn ($node) => in_array($node->getModel()->getKey(), $filteredModelIds));

            View::share('tree', $filteredTree);
            View::share('originalModels', PairOptions::toPairs($this->getTree()->pluck('id', fn (NestedNode $nestedNode) => $nestedNode->getBreadCrumbLabel())));
        } else {
            // Used for duplicate action
            View::share('originalModels', ModelReferencePresenter::toSelectValues($model::all(), false, false));
            View::share('models', $this->indexModels());
        }

        return $this->resource->getIndexView();
    }

    private function getTree(): iterable
    {
        // TODO: work with the indexRepository
        // app($resource->indexRepository(), ['resourceKey' => $resourceKey])->applyFilters(request()->all())->getNestableResults()

        return app(NestableRepository::class)->getTree($this->resource::resourceKey());
    }

    protected function indexModels(): Paginator
    {
        // Apply filtering - this also includes default sorting
        $this->filters()->apply($builder = $this->managedModelClass()::query());

        if ($this->managedModelClassInstance() instanceof Visitable) {
            $builder->with(['urls']);
        }

        if ($this->managedModelClassInstance() instanceof Taggable) {
            $builder->with(['tags']);
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
                return is_array($query->getQuery()->orders) && count($query->getQuery()->orders) < 1
                    ? $query->orderBy('updated_at', 'DESC')
                    : $query;
            }));
        }

        return $filters;
    }
}
