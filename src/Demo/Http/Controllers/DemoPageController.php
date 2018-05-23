<?php

namespace Chief\Demo\Http\Controllers;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Chief\Pages\Application\CreatePage;
use Chief\Pages\Page;
use Chief\Pages\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Thinktomorrow\AssetLibrary\Models\Asset;
use Thinktomorrow\Chief\App\Http\Requests\PageCreateRequest;
use Chief\Pages\Application\UpdatePage;
use Thinktomorrow\Chief\App\Http\Requests\PageUpdateRequest;
use Chief\Common\Traits\CheckPreviewTrait;

class DemoPageController extends Controller
{
    use CheckPreviewTrait;

    public function index()
    {
        if($this->isPreviewAllowed())
        {
            $pages = Page::all();
        }else{
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

        if(!$page) return redirect()->route('demo.pages.index')->with('note.default', 'Geen resultaten gevonden.');

        return view('demo::pagedetail', compact('page'));
    }
}