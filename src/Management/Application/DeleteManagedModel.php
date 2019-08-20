<?php

namespace Thinktomorrow\Chief\Management\Application;

use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\Chief\States\State\StatefulContract;

class DeleteManagedModel
{
    public function handle(ManagedModel $model)
    {
        // For stateful transitions we will apply this deletion as a state
        if($model instanceof StatefulContract) {
            (new PageState($model))->apply('delete');
            $model->save();
        }

        Audit::activity()
            ->performedOn($model)
            ->log('deleted');

        $model->delete();
    }
}
