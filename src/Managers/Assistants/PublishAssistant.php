<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;

trait PublishAssistant
{
    abstract protected function guard(string $action, $model = null);
    abstract protected function generateRoute(string $action, $model, ...$parameters): string;

    public function routesPublishAssistant(): array
    {
        return [
            ManagedRoute::post('publish','publish/{id}'),
            ManagedRoute::post('unpublish','unpublish/{id}'),
        ];
    }

    public function routePublishAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if(!$this->canPublishAssistant($action, $model)) return null;

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canPublishAssistant(string $action, $model = null): bool
    {
        if(!in_array($action, ['publish', 'unpublish'])) return false;

        try {
            $this->authorize('update-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        if(!$model || !$model instanceof StatefulContract) return false;

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
        $model = $this->managedModelClass()::findOrFail($id);

        $this->guard('publish', $model);

        $model->publish();

        Audit::activity()->performedOn($model)->log('published');

        return redirect()->to($this->route('index'))->with('messages.success', $model->adminLabel('title') . ' is online geplaatst.');
    }

    public function unpublish(Request $request, $id)
    {
        $model = $this->managedModelClass()::published()->findOrFail($id);

        $this->guard('unpublish', $model);

        $model->unpublish();

        Audit::activity()->performedOn($model)->log('unpublished');

        return redirect()->to($this->route('index'))->with('messages.success', $model->adminLabel('title') . ' is offline gehaald.');
    }
}
