<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Application\SortModels;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;

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
        // TODO: check if model has sortable trait...
        return in_array($action, ['sort-index', 'index-for-sorting']);
    }

    public function filtersSortAssistant(): Filters
    {
        return new Filters([
            HiddenFilter::make('sortIndex', function ($query) {
                return $query->orderBy('order', 'ASC');
            }),
        ]);
    }

    public function sortIndex(Request $request)
    {
        if(!$request->indices) {
            throw new \InvalidArgumentException('Missing arguments [indices] for sorting request.');
        }

        app(SortModels::class)->handle($this->managedModelClass(), $request->indices);

        return response()->json([
            'message' => 'models sorted.'
        ]);
    }

    public function indexForSorting()
    {
        $modelClass = $this->managedModelClass();
        $model = new $modelClass();

        return view('chief::back.managers.index-for-sorting', [
            'manager' => $this,
            'model'   => $model,
            'models'  => $this->indexModelsForSorting(),
        ]);
    }

    protected function indexModelsForSorting(): Collection
    {
        $this->filters()->apply($builder = $this->managedModelClass()::query());

        return $builder->get();
    }
}
