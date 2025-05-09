<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlHelper;

trait ArchiveAssistant
{
    abstract protected function guard(string $action, $model = null);

    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesArchiveAssistant(): array
    {
        return [
            ManagedRoute::get('archive_modal', 'archive_modal/{id}'),
            ManagedRoute::get('archive_index'),
        ];
    }

    public function canArchiveAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['archive_modal', 'archive_index'])) {
            return false;
        }

        try {
            $this->authorize('view-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        if ($action === 'archive_modal') {
            return true;
        }

        if ($action === 'archive_index') {
            // Archive index is only visitable when there is at least one model archived.
            if (public_method_exists($this->managedModelClass(), 'scopeArchived')) {
                return $this->managedModelClass()::archived()->count() > 0;
            }

            return false;
        }

        return true;
    }

    public function archiveModal(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        return response()->json([
            'data' => view('chief::manager._transitions.modals.archive-modal-content', [
                'manager' => $this,
                'model' => $model,
                'resource' => $this->registry->findResourceByModel($model::class),
                'stateConfig' => $model->getStateConfig(PageState::KEY),
                'targetModels' => UrlHelper::allOnlineModels(false, $model),
            ])->render(),
        ]);
    }

    public function archiveIndex(Request $request)
    {
        $this->guard('archive_index');

        View::share('is_archive_index', true);
        View::share('manager', $this);
        View::share('resource', $this->resource);
        View::share('table', $this->resource->getArchivedIndexTable());

        return $this->resource->getIndexView();
    }
}
