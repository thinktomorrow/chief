<?php

namespace App\Http\Controllers\Back;


use App\Http\Controllers\Controller;
use Chief\Models\Asset;
use Illuminate\Http\Request;

class MediaController extends Controller
{

    public function upload(Request $request)
    {
        $asset = Asset::upload($request->file('image'));

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        Asset::remove($request->get('imagestoremove'));

        return redirect()->back();
    }
}