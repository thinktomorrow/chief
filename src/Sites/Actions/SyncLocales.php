<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\Events\LocalesUpdated;
use Thinktomorrow\Chief\Sites\MultiSiteable;

class SyncLocales
{
    public function handle(MultiSiteable & ReferableModel $model, array $locales): void
    {
        $previousState = $model->getSiteLocales();

        $model->saveSiteLocales($model, $locales);

        event(new LocalesUpdated($model->modelReference(), $model->getSiteLocales(), $previousState));
    }
}