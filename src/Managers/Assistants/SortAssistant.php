<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelsSorted;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Shared\Helpers\SortModels;

trait SortAssistant
{
    public function routesSortAssistant(): array
    {
        return [
            ManagedRoute::get('index-for-sorting'),
            ManagedRoute::post('sort-index'),
            ManagedRoute::post('move-index'),
        ];
    }

    public function canSortAssistant(string $action, $model = null): bool
    {
        return in_array($action, ['sort-index', 'index-for-sorting', 'move-index'])
            && ($model && public_method_exists($model, 'isSortable') && $model->isSortable());
    }

    public function filtersSortAssistant(): Filters
    {
        $modelClass = $this->managedModelClass();
        $model = new $modelClass;

        if (! $this->can('sort-index', $model)) {
            return new Filters;
        }

        return new Filters([
            HiddenFilter::make('sortIndex', function ($query) use ($model) {
                return $query->orderBy($model->sortableAttribute(), 'ASC');
            }),
        ]);
    }

    public function sortIndex(Request $request)
    {
        if (! $request->indices) {
            throw new \InvalidArgumentException('Missing arguments [indices] for sorting request.');
        }

        app(SortModels::class)->handleByModel(
            $this->managedModelClassInstance(),
            $request->indices,
            $this->managedModelClassInstance()->sortableAttribute(),
            $this->managedModelClassInstance()->getKeyName(),
            $this->managedModelClassInstance()->getKeyType() == 'int',
        );

        event(new ManagedModelsSorted($this->resource::resourceKey(), $request->indices));

        return response()->json([
            'message' => 'models sorted.',
        ]);
    }

    public function sortItem(Request $request)
    {
        if (! $request->index) {
            throw new \InvalidArgumentException('Missing arguments [index] for item sorting request.');
        }

        app(SortModels::class)->handleItem($this->managedModelClassInstance(), $request->index, $this->managedModelClassInstance()->sortableAttribute());

        return response()->json([
            'message' => 'models sorted.',
        ]);
    }

    public function moveIndex(Request $request)
    {
        if (! $request->input('itemId') || ! $request->has('parentId')) {
            throw new \InvalidArgumentException('Missing arguments [itemId or parentId] for moveIndex request.');
        }

        $instance = $this->managedModelClassInstance();

        $this->managedModelClass()::findOrFail($request->input('itemId'))->update([
            'parent_id' => $request->input('parentId', null),
            $instance->sortableAttribute() => $request->input('order', 0),
        ]);

        return response()->json([
            'message' => 'Item moved to new parent',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexForSorting()
    {
        View::share('is_reorder_index', true);

        View::share('manager', $this);
        View::share('resource', $this->resource);

        // For the time being, while we work on the reorder table, we'll use the old sorting table
        View::share('models', $this->indexModelsForSorting());
        View::share('model', $this->managedModelClassInstance());

        //        return view('chief::manager.index-for-sorting');

        // TODO: this is the future sorting table but not quite there yet...
        View::share('table', $this->resource->getReorderTable());

        return $this->resource->getIndexView();
    }

    protected function indexModelsForSorting(): LengthAwarePaginator
    {
        $this->filters()->apply($builder = $this->managedModelClass()::query());

        return $builder->paginate(1000)->withQueryString();
    }
}
