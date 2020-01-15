<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Relations\Relation;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\States\State\StatefulContract;

class DeleteManagedModel
{
    public function handle(ManagedModel $model)
    {
        try {
            DB::beginTransaction();

            // For stateful transitions we will apply this deletion as a state
            if ($model instanceof StatefulContract) {
                (new PageState($model, PageState::KEY))->apply('delete');
                $model->save();
            }

            Relation::deleteRelationsOf($model->getMorphClass(), $model->id);

            // Mark the slug as deleted to avoid any conflict with newly created modules with the same slug.
            if ($model instanceof Module) {
                $model->update([
                    'slug' => $model->slug . $this->appendDeleteMarker(),
                ]);
            }

            if ($model instanceof ProvidesUrl) {
                UrlRecord::getByModel($model)->each->delete();
            }

            if ($model instanceof Page) {
                Module::where('page_id', $model->id)->delete();
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

    private function appendDeleteMarker(): string
    {
        return '_DELETED_' . time();
    }
}
