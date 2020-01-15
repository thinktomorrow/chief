<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\ManagedModel;

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
