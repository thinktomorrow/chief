<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\System\HealthMonitor\Monitor;

class DashboardController extends Controller
{
    public function show()
    {
        app(Monitor::class)->check();

        return view('chief::back.dashboard');
    }

    public function gettingStarted()
    {
        return view('chief::back.dashboard');
    }
}
