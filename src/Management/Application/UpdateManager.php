<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\FieldValidator;
use Thinktomorrow\Chief\Management\ModelManager;

class UpdateManager
{
    use StoringAndUpdatingFields;

    public function handle(ModelManager $manager, Request $request)
    {
        $manager->guard('update');

        $request = $manager->updateRequest($request);

        app(FieldValidator::class)->validate($manager->fields(), $request);

        if (method_exists($manager, 'beforeUpdate')) {
            $manager->beforeUpdate($request);
        }

        $this->handleFields($manager, $request);

        // Handle any custom save methods
        $this->handleCustomSaves($manager, $request);

        if (method_exists($manager, 'afterUpdate')) {
            $manager->afterUpdate($request);
        }
    }
}
