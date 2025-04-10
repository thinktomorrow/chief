<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\Events\ModelSitesUpdated;
use Thinktomorrow\Chief\Sites\HasSiteLocales;

class SaveSiteLocales
{
    public function handle(HasSiteLocales&ReferableModel $model, array $locales): void
    {
        $previousState = $model->getSiteLocales();

        $model->setSiteLocales(array_values(array_unique($locales)));
        $model->save();

        event(new ModelSitesUpdated($model->modelReference(), $model->getSiteLocales(), $previousState));
    }
}
