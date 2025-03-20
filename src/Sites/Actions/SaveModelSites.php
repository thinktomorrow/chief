<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\Events\ModelSitesUpdated;

class SaveModelSites
{
    public function handle(BelongsToSites&ReferableModel $model, array $locales): void
    {
        $previousState = $model->getSiteLocales();

        $model->setSiteLocales($locales);
        $model->save();

        event(new ModelSitesUpdated($model->modelReference(), $model->getSiteLocales(), $previousState));
    }
}
