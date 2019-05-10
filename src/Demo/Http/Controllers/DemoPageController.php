<?php

namespace Thinktomorrow\Chief\Demo\Http\Controllers;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Concerns\Publishable\CheckPreviewTrait;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageRepository;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Urls\ChiefResponse;

class DemoPageController extends Controller
{
    use CheckPreviewTrait;

    public function show(Request $request)
    {
        return ChiefResponse::fromSlug($request->slug);
//        if ($this->isPreviewAllowed()) {
//            $page = Page::findBySlug($request->slug);
//        } else {
//            $page = Page::findPublishedBySlug($request->slug);
//        }
//
//        if (!$page) {
//            return redirect()->route('demo.pages.index')->with('note.default', 'Geen resultaten gevonden.');
//        }
//
//        return view('demo::pagedetail', compact('page'));
    }
}
