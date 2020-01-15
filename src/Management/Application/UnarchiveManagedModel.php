<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\ManagedModel;

class UnarchiveManagedModel
{
    public function handle(ManagedModel $model)
    {
        (new PageState($model, PageState::KEY))->apply('unarchive');
        $model->save();

        Audit::activity()
            ->performedOn($model)
            ->log('unarchived');
    }
}
