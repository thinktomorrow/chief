<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\HealthMonitor\Monitor;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function show()
    {
        Monitor::check();
        return view('chief::back.dashboard');
    }

    public function gettingStarted()
    {
        return view('chief::back.dashboard');
    }
}
