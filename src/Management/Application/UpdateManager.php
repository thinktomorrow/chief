<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Manager;

class UpdateManager
{
    public function handle(Manager $manager, Request $request)
    {
        $manager->guard('update');

        $request = $manager->updateRequest($request);

        $manager->fieldsWithAssistantFields()->validate($request->all());

        if (method_exists($manager, 'beforeUpdate')) {
            $manager->beforeUpdate($request);
        }

        $manager->saveFields($request);

        if (method_exists($manager, 'afterUpdate')) {
            $manager->afterUpdate($request);
        }
    }
}
