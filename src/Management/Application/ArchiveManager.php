<?php

namespace Thinktomorrow\Chief\Management\Application;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Management\Manager;

class ArchiveManager
{
    public function handle(Manager $manager, Request $request)
    {
        $manager->guard('archive');

        $manager->archive();
    }
}
