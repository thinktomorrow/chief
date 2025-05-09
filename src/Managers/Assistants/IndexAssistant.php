<?php

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Admin\Users\VisitedUrl;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\Managers\DiscoverTraitMethods;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

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

    public function index(Request $request)
    {
        $this->guard('index');

        app(VisitedUrl::class)->add(request()->fullUrl());

        View::share('manager', $this);
        View::share('resource', $this->resource);
        View::share('model', $model = $this->managedModelClassInstance());
        View::share('table', $this->resource->getIndexTable());

        return $this->resource->getIndexView();
    }

    public function filters(): Filters
    {
        return $this->defaultFilters();
    }

    private function defaultFilters(): Filters
    {
        $filters = new Filters;

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
