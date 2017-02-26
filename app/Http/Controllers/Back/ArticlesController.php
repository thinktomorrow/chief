<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;

class ArticlesController extends Controller
{
    public function index()
    {
        // TODO: create articles management
        return view('back.home');
    }
}