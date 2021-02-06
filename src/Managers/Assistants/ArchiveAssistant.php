<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;

trait ArchiveAssistant
{
    abstract protected function guard(string $action, $model = null);
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesArchiveAssistant(): array
    {
        return [
            ManagedRoute::post('archive','archive/{id}'),
            ManagedRoute::post('unarchive','unarchive/{id}'),
            ManagedRoute::get('archive_index'),
        ];
    }

    public function canArchiveAssistant(string $action, $model = null): bool
    {
        if(!in_array($action, ['archive', 'unarchive', 'archive_index'])) return false;

        try {
            $this->authorize('update-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        if($action === 'archive_index') {
            // Archive index is only visitable when there is at least one model archived.
            if(public_method_exists($this->managedModelClass(), 'scopeArchived')) {
                return $this->managedModelClass()::archived()->count() > 0;
            }
            return false;
        }

        if(!$model || !$model instanceof StatefulContract) return false;

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
                    return ($record->locale == $urlRecord->locale && !$record->isRedirect());
                })) {
                    $urlRecord->redirectTo($targetRecord);
                }
            }

            // Cast all existing records to the new owning model
            $archivedUrlRecords->each(function(UrlRecord $urlRecord) use($redirectModel){
                $urlRecord->changeOwningModel($redirectModel);
                $urlRecord->save();
            });


        }

        $model->archive();

        Audit::activity()->performedOn($model)->log('archived');

        return redirect()->to($this->route('index'))->with('messages.success', $model->adminLabel('title') . ' is gearchiveerd.');
    }

    public function unarchive(Request $request, $id)
    {
        $model = $this->managedModelClass()::archived()->findOrFail($id);

        $this->guard('unarchive', $model);

        $model->unarchive();

        Audit::activity()->performedOn($model)->log('unarchived');

        return redirect()->to($this->route('index'))->with('messages.success', $model->adminLabel('title') . ' is uit het archief gehaald.');
    }

    public function archiveIndex(Request $request)
    {
        $modelClass = $this->managedModelClass();
        $model = new $modelClass();

        return view('chief::back.managers.index', [
            'manager' => $this,
            'model'   => $model,
            'models'  => $this->managedModelClass()::archived()->paginate(20),
        ]);
    }
}
