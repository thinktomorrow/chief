<?php

namespace Thinktomorrow\Chief\Management\Application;

use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\Chief\Management\Events\ManagedModelArchived;

class ArchiveManagedModel
{
    public function handle(ManagedModel $model)
    {
        (new PageState($model))->apply('archive');
        $model->save();

        Audit::activity()
            ->performedOn($model)
            ->log('archived');
    }
}
