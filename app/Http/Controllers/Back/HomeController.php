<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function show()
    {
        return view('back.home');
    }
}