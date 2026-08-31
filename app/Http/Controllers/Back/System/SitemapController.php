<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\System;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Site\Sitemap\SitemapFiles;

class SitemapController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        $this->authorize('view-page');

        $sitemapFiles = app(SitemapFiles::class)->allWithin(public_path());

        return view('chief::admin.sitemap.show', [
            'sitemapFiles' => $sitemapFiles,
        ]);
    }

    public function generate()
    {
        Artisan::call('chief:sitemap');
        Artisan::call('chief:image-sitemap');

        return response()->json([
            'status' => 200,
        ]);
    }
}
