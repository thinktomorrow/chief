<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Manager;

class DeleteManager
{
    public function handle(Manager $manager, Request $request)
    {
        $manager->guard('delete');

        $manager->delete();
    }
}
