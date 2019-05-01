<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Manager;

class UpdateManager
{
    use StoringAndUpdatingFields;

    public function handle(Manager $manager, Request $request)
    {
        $manager->guard('update');

        $request = $manager->updateRequest($request);

        $manager->fieldsWithAssistantFields()->validate($request->all());

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
