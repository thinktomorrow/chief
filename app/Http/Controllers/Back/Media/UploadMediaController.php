<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Media;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Pages\PageRepository;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class UploadMediaController extends Controller
{
    public function store(Request $request)
    {
        dd($request->all());
    }

}
