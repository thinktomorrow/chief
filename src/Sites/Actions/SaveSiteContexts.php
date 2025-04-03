<?php

namespace Thinktomorrow\Chief\Sites\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\HasSiteContexts;

class SaveSiteContexts
{
    public function handle(HasSiteContexts&ReferableModel $model, array $contextsByLocale): void
    {
        $model->setSiteContexts($contextsByLocale);
        $model->save();
    }
}
