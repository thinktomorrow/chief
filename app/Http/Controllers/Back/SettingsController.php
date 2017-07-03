<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function show()
    {
        return view('back.settings');
    }


}
