<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferencePresenter;
use Thinktomorrow\Chief\Pages\Page;

class InternalLinksController extends Controller
{
    public function index(Request $request)
    {
        // Fetch the links for specific locale
        if ($request->has('locale')) {
            app()->setLocale($request->get('locale'));
        }

        $links = Page::all()->map(function ($page) {
            return [
                'name' => $page->menuLabel(),
                'url' => $page->menuUrl(),
            ];
        });

        return response()->json($links->prepend(['name' => '...', 'url' => ''])->all());
    }
}
