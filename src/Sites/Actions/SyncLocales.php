<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\Events\LocalesUpdated;

class SyncLocales
{
    public function handle(BelongsToSites&ReferableModel $model, array $locales): void
    {
        $previousState = $model->getSiteIds();

        $model->saveSiteLocales($model, $locales);

        event(new LocalesUpdated($model->modelReference(), $model->getSiteIds(), $previousState));
    }
}
