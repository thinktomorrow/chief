<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\DeleteContext;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelDeleted;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelQueuedForDeletion;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class DeleteModel
{
    private DetachAsset $detachAsset;

    private ContextApplication $contextApplication;

    public function __construct(DetachAsset $detachAsset, ContextApplication $contextApplication)
    {
        $this->detachAsset = $detachAsset;
        $this->contextApplication = $contextApplication;
    }

    public function onManagedModelQueuedForDeletion(ManagedModelQueuedForDeletion $e): void
    {
        $this->handle($e->modelReference->instance());
    }

    public function handle(Model $model): void
    {
        try {
            DB::beginTransaction();

            if ($model instanceof HasAsset) {
                $this->detachAsset->handleAll($model);
            }

            if ($model instanceof ContextOwner) {
                $contexts = app(ContextRepository::class)->getByOwner($model->modelReference());

                foreach ($contexts as $context) {
                    $this->contextApplication->delete(new DeleteContext($context->id));
                }
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
