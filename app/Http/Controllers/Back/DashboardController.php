<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function show()
    {
        return view('back.dashboard');
    }

    public function gettingStarted()
    {
        return view('back.dashboard');
    }
}