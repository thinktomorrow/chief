<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\ModelManager;

class DeleteManager
{
    public function handle(ModelManager $manager, Request $request)
    {
        $manager->guard('delete');

        $manager->delete();
    }
}