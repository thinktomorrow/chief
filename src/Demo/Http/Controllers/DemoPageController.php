<?php

namespace Thinktomorrow\Chief\Demo\Http\Controllers;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Concerns\Publishable\CheckPreviewTrait;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageRepository;
use Illuminate\Http\Request;

class DemoPageController extends Controller
{
    use CheckPreviewTrait;

    public function index()
    {
        if ($this->isPreviewAllowed()) {
            $pages = Page::all();
        } else {
            $pages = Page::getAllPublished();
        }

        return view('demo::index', compact('pages'));
    }

    public function show(Request $request)
    {
        if ($this->isPreviewAllowed()) {
            $page = Page::findBySlug($request->slug);
        } else {
            $page = Page::findPublishedBySlug($request->slug);
        }

        if (!$page) {
            return redirect()->route('demo.pages.index')->with('note.default', 'Geen resultaten gevonden.');
        }

        return view('demo::pagedetail', compact('page'));
    }
}
