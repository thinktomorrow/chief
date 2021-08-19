<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Actions\SortModels;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait SortAssistant
{
    public function routesSortAssistant(): array
    {
        return [
            ManagedRoute::get('index-for-sorting'),
            ManagedRoute::post('sort-index'),
        ];
    }

    public function canSortAssistant(string $action, $model = null): bool
    {
        return (in_array($action, ['sort-index', 'index-for-sorting'])
            && ($model && public_method_exists($model, 'isSortable') && $model->isSortable()));
    }

    public function filtersSortAssistant(): Filters
    {
        $model = new $this->managedModelClass();
        if (! $this->can('sort-index', $model)) {
            return new Filters();
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

        app(SortModels::class)->handle((new $this->managedModelClass()), $request->indices, (new $this->managedModelClass())->sortableAttribute());

        return response()->json([
            'message' => 'models sorted.',
        ]);
    }

    public function sortItem(Request $request)
    {
        if (! $request->index) {
            throw new \InvalidArgumentException('Missing arguments [index] for item sorting request.');
        }

        app(SortModels::class)->handleItem((new $this->managedModelClass()), $request->index, (new $this->managedModelClass())->sortableAttribute());

        return response()->json([
            'message' => 'models sorted.',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexForSorting()
    {
        $modelClass = $this->managedModelClass();
        $model = new $modelClass();

        return view('chief::manager.index-for-sorting', [
            'manager' => $this,
            'model' => $model,
            'models' => $this->indexModelsForSorting(),
        ]);
    }

    protected function indexModelsForSorting(): LengthAwarePaginator
    {
        $this->filters()->apply($builder = $this->managedModelClass()::query());

        return $builder->paginate(1000);
    }
}
