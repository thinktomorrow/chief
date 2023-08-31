<?php

namespace Thinktomorrow\Chief\Assets\App\Http;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class MediaGalleryController extends Controller
{
    public function index()
    {
        return view('chief::admin.mediagallery.index');
    }
}
