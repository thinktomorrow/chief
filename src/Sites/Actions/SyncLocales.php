<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Sites\Events\LocalesUpdated;
use Thinktomorrow\Chief\Sites\MultiSiteable;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class SyncLocales
{
    public function handle(MultiSiteable & ReferableModel $model, array $locales): void
    {
        $previousState = $model->getLocales();

        $model->saveLocales($model, $locales);

        event(new LocalesUpdated($model->modelReference(), $model->getLocales(), $previousState));
    }
}
