<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\System;

use Illuminate\Support\Facades\Artisan;
use Thinktomorrow\Chief\Site\Sitemap\SitemapFiles;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function index()
    {
        $this->authorize('view-page');

        $sitemapFiles = app(SitemapFiles::class)->allWithin(public_path());

        return view('chief::back.system.sitemap.show', [
            'sitemapFiles' => $sitemapFiles
        ]);
    }

    public function generate()
    {
        Artisan::call('chief:sitemap');

        return response()->json([
            'status' => 200
        ]);
    }
}
