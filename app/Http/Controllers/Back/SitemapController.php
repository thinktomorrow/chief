<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Illuminate\Support\Facades\Artisan;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function index()
    {
        $this->authorize('view-page');

        $sitemaps = collect(config('translatable.locales'))->map(function($locale){
            return url('/').'/sitemap-'.$locale.'.xml';
        });

        return view('chief::back.sitemap.index', [
            'sitemaps' => $sitemaps
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
