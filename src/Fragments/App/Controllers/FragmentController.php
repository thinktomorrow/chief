<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\App\Components\Fragments;

class FragmentController
{
    // Refresh fragments index after sidebar edit
    public function refreshIndex($contextId)
    {
        return (new Fragments($contextId))->render()->render();
    }
}
