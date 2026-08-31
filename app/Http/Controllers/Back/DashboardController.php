<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * @return Factory|View
     */
    public function show()
    {
        return view('chief::admin.dashboard');
    }

    /**
     * @return Factory|View
     */
    public function gettingStarted()
    {
        return view('chief::admin.dashboard');
    }
}
