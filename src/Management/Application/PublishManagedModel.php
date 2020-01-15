<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\ManagedModel;

class PublishManagedModel
{
    public function handle(ManagedModel $model)
    {
        (new PageState($model))->apply('publish');
        $model->save();

        Audit::activity()
            ->performedOn($model)
            ->log('published');
    }
}
