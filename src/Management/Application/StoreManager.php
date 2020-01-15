<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Manager;

class StoreManager
{
    public function handle(Manager $manager, Request $request): Manager
    {
        $manager->guard('store');

        $request = $manager->storeRequest($request);

        $manager->fieldsWithAssistantFields()->validate($request->all());

        if (method_exists($manager, 'beforeStore')) {
            $manager->beforeStore($request);
        }

        $manager->saveFields($request);

        if (method_exists($manager, 'afterStore')) {
            $manager->afterStore($request);
        }

        // Since the model doesn't exist yet, it is now created via the save method
        // For the store we return the new manager which is now connected to the created model instance
        return $manager;
    }
}
