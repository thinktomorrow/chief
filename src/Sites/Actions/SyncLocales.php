<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\Events\LocalesUpdated;
use Thinktomorrow\Chief\Sites\BelongsToSites;

class SyncLocales
{
    public function handle(BelongsToSites & ReferableModel $model, array $locales): void
    {
        $previousState = $model->getSites();

        $model->saveSiteLocales($model, $locales);

        event(new LocalesUpdated($model->modelReference(), $model->getSites(), $previousState));
    }
}
