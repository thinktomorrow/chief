<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\Chief\Fragments\Actions\DeleteContext;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelDeleted;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\PageState\WithPageState;

class DeleteModel
{
    private DetachAsset $detachAsset;
    private DeleteContext $deleteContext;

    public function __construct(DetachAsset $detachAsset, DeleteContext $deleteContext)
    {
        $this->detachAsset = $detachAsset;
        $this->deleteContext = $deleteContext;
    }

    public function handle(Model $model): void
    {
        try {
            DB::beginTransaction();

            // For stateful transitions we will apply this deletion as a state
            if ($model instanceof WithPageState) {
                PageState::make($model)->apply('delete');
                $model->save();
            }

            // TODO: schedule for deletion instead of instantly deleting all relations and stuff...
            // so the user has a small window of recovery

            if ($model instanceof HasAsset) {
                $this->detachAsset->detachAll($model);
            }

            if ($model instanceof FragmentsOwner) {
                $this->deleteContext->handle($model);
            }

            // TODO: when deleting a model, where should the urls redirect to? Or expect here a 404?
            // Delete any related urls...
            if ($model instanceof Visitable) {
                UrlRecord::getByModel($model)->each->delete();
            }

            Audit::activity()
                ->performedOn($model)
                ->log('deleted');

            $model->delete();

            event(new ManagedModelDeleted($model->modelReference()));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
