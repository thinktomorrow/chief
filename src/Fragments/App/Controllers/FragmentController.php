<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Thinktomorrow\Chief\Fragments\UI\Components\Fragments;

class FragmentController
{
    // Refresh fragments index after sidebar edit
    public function refreshIndex($contextId)
    {
        return (new Fragments($contextId))->render()->render();
    }
}