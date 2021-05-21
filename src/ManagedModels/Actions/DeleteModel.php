<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class DeleteModel
{
    /** @var DetachAsset */
    private DetachAsset $detachAsset;

    public function __construct(DetachAsset $detachAsset)
    {
        $this->detachAsset = $detachAsset;
    }

    public function handle(ManagedModel $model): void
    {
        try {
            DB::beginTransaction();

            // For stateful transitions we will apply this deletion as a state
            if ($model instanceof StatefulContract) {
                PageState::make($model)->apply('delete');
                $model->save();
            }

            // TODO: schedule for deletion instead of instantly deleting all relations and stuff...
            // so the user has a small window of recovery

            if ($model instanceof HasAsset) {
                $this->detachAsset->detachAll($model);
            }

            // TODO: soft-delete the context of this model... (=fragments)
            // TODO: check for usage in fragments???? 

//            Relation::deleteRelationsOf($model->getMorphClass(), $model->id);

            // TODO: when deleting a model, where should the urls redirect to? Or expect here a 404?
            // Delete any related urls...
            if ($model instanceof Visitable) {
                UrlRecord::getByModel($model)->each->delete();
            }

            Audit::activity()
                ->performedOn($model)
                ->log('deleted');

            $model->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
