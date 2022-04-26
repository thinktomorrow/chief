<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUnPublished;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\PageState\WithPageState;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait PublishAssistant
{
    abstract protected function guard(string $action, $model = null);
    abstract protected function generateRoute(string $action, $model, ...$parameters): string;

    public function routesPublishAssistant(): array
    {
        return [
            ManagedRoute::post('publish', 'publish/{id}'),
            ManagedRoute::post('unpublish', 'unpublish/{id}'),
        ];
    }

    public function routePublishAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! $this->canPublishAssistant($action, $model)) {
            return null;
        }

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canPublishAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['publish', 'unpublish'])) {
            return false;
        }

        try {
            $this->authorize('update-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        if (! $model || ! $model instanceof WithPageState) {
            return false;
        }

        return PageState::make($model)->can($action);
    }

    public function filtersPublishAssistant(): Filters
    {
        $modelClass = $this->managedModelClass();
        if (Schema::hasColumn((new $modelClass)->getTable(), 'published_at')) {
            return new Filters([
                HiddenFilter::make('publish', function ($query) {
                    return $query->orderBy('published_at', 'DESC');
                }),
            ]);
        }

        return new Filters();
    }

    public function publish(Request $request, $id)
    {
        $model = $this->managedModelClass()::withoutGlobalScopes()->findOrFail($id);

        $this->guard('publish', $model);

        $model->publish();

        event(new ManagedModelPublished($model->modelReference()));

        Audit::activity()->performedOn($model)->log('published');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->resource->getPageTitle($model) . ' is online geplaatst.',
            ]);
        }

        return redirect()->to($this->route('index'))->with('messages.success', $this->resource->getPageTitle($model) . ' is online geplaatst.');
    }

    public function unpublish(Request $request, $id)
    {
        $model = $this->managedModelClass()::published()->findOrFail($id);

        $this->guard('unpublish', $model);

        $model->unpublish();

        event(new ManagedModelUnPublished($model->modelReference()));

        Audit::activity()->performedOn($model)->log('unpublished');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->resource->getPageTitle($model) . ' is offline gehaald.',
            ]);
        }

        return redirect()->to($this->route('index'))->with('messages.success', $this->resource->getPageTitle($model) . ' is offline gehaald.');
    }
}
