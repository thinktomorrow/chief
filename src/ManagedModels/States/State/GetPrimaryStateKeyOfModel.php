<?php

namespace Thinktomorrow\Chief\ManagedModels\States\State;

use Thinktomorrow\Chief\Managers\Register\Registry;

class GetPrimaryStateKeyOfModel
{
    public static function get(string $resourceKey): ?string
    {
        $resource = app(Registry::class)->resource($resourceKey);
        $modelClassName = $resource::modelClassName();
        $model = new $modelClassName;

        if (! $model instanceof StatefulContract) {
            return null;
        }

        return $model->getStateKeys()[0];
    }
}
