<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\Events\ModelSitesUpdated;

class SaveModelSites
{
    public function handle(BelongsToSites&ReferableModel $model, array $siteIds): void
    {
        $previousState = $model->getSiteIds();

        $model->setSiteIds($siteIds);
        $model->save();

        event(new ModelSitesUpdated($model->modelReference(), $model->getSiteIds(), $previousState));
    }
}
