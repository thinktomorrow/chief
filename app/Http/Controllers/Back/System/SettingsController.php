<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\System;

use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function show()
    {
        return view('chief::back.system.settings');
    }
}
