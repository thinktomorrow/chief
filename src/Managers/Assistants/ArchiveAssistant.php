<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelArchived;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\ManagedModels\States\PageState\WithPageState;

trait ArchiveAssistant
{
    abstract protected function guard(string $action, $model = null);
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesArchiveAssistant(): array
    {
        return [
            ManagedRoute::get('archive_modal', 'archive_modal/{id}'),
            ManagedRoute::post('archive', 'archive/{id}'),
            ManagedRoute::post('unarchive', 'unarchive/{id}'),
            ManagedRoute::get('archive_index'),
        ];
    }

    public function canArchiveAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['archive', 'archive_modal', 'unarchive', 'archive_index'])) {
            return false;
        }

        try {
            $this->authorize('update-page');
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

        if (! $model || ! $model instanceof WithPageState) {
            return false;
        }

        return PageState::make($model)->can($action);
    }

    public function archive(Request $request, $id)
    {
        $model = $this->managedModelClass()::unarchived()->findOrFail($id);

        $this->guard('archive', $model);

        // If a redirect_id is passed along the request, it indicates
        // the admin wants this page to be redirected to another one.
        if ($redirectReference = $request->input('redirect_id')) {
            $redirectModel = ModelReference::fromString($redirectReference)->instance();
            $archivedUrlRecords = UrlRecord::getByModel($model);
            $targetRecords = UrlRecord::getByModel($redirectModel);

            // Ok now get all urls from this model and point them to the new records
            foreach ($archivedUrlRecords as $urlRecord) {
                if ($targetRecord = $targetRecords->first(function ($record) use ($urlRecord) {
                    return ($record->locale == $urlRecord->locale && ! $record->isRedirect());
                })) {
                    $urlRecord->redirectTo($targetRecord);
                }
            }

            // Cast all existing records to the new owning model
            $archivedUrlRecords->each(function (UrlRecord $urlRecord) use ($redirectModel) {
                $urlRecord->changeOwningModel($redirectModel);
                $urlRecord->save();
            });
        }

        $model->archive();

        event(new ManagedModelArchived($model->modelReference()));

        Audit::activity()->performedOn($model)->log('archived');

        return redirect()->to($this->route('index'))->with('messages.success', $this->resource->getPageTitle($model) . ' is gearchiveerd.');
    }

    public function unarchive(Request $request, $id)
    {
        $model = $this->managedModelClass()::archived()->findOrFail($id);

        $this->guard('unarchive', $model);

        $model->unarchive();

        Audit::activity()->performedOn($model)->log('unarchived');

        return redirect()->to($this->route('index'))->with('messages.success', $this->resource->getPageTitle($model) . ' is uit het archief gehaald.');
    }

    public function archiveModal(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        return response()->json([
            'data' => view('chief::manager._transitions.modals.archive-modal-content', [
                'manager' => $this,
                'model' => $model,
                'resource' => $this->registry->findResourceByModel($model::class),
                'targetModels' => UrlHelper::allOnlineModels(false, $model),
            ])->render(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function archiveIndex()
    {
        View::share('manager', $this);
        View::share('resource', $this->resource);
        View::share('models', $this->managedModelClass()::archived()->paginate(20)->withQueryString());
        View::share('model', $this->managedModelClassInstance());

        return $this->resource->getIndexView();
    }
}
