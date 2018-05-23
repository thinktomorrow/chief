<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Chief\Common\Relations\RelatedCollection;
use Chief\Pages\Application\CreatePage;
use Chief\Pages\Page;
use Chief\Pages\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\PageCreateRequest;
use Chief\Pages\Application\UpdatePage;
use Thinktomorrow\Chief\App\Http\Requests\PageUpdateRequest;

class PagesController extends Controller
{
    public function index()
    {
        $published  = Page::where('published', 1)->paginate(10);
        $drafts     = Page::where('published', 0)->get();

        return view('back.pages.index', compact('published', 'drafts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page = new Page();
        $page->existingRelationIds = collect([]);
        $relations = RelatedCollection::availableChildren($page)->flattenForGroupedSelect()->toArray();

        return view('back.pages.create',['page' => $page, 'relations' => $relations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(PageCreateRequest $request)
    {
        $page = app(CreatePage::class)->handle($request->trans);

        return redirect()->route('back.pages.index')->with('messages.success', $page->title .' is aangemaakt');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $page->injectTranslationForForm();

        $page->existingRelationIds = RelatedCollection::relationIds($page->children());
        $relations = RelatedCollection::availableChildren($page)->flattenForGroupedSelect()->toArray();

        return view('back.pages.edit', compact('page', 'relations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function update(PageUpdateRequest $request, $id)
    {
        $page = app(UpdatePage::class)->handle($id, $request->trans, $request->relations);

        return redirect()->route('back.pages.index')->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "'.$page->title .'" werd aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        $page->delete();
        $message = 'Het item werd verwijderd.';

        return redirect()->route('back.pages.index')->with('messages.warning', $message);
    }

    public function publish(Request $request)
    {
        $page    = Page::findOrFail($request->get('id'));
        $published  = true === !$request->checkboxStatus; // string comp. since bool is passed as string

        ($published) ? $page->publish() : $page->draft();

        return redirect()->back();

//        return response()->json([
//            'message' => $published ? 'nieuwsartikel werd online gezet' : 'nieuwsartikel werd offline gehaald',
//            'published'=> $published,
//            'id'=> $page->id
//        ],200);
    }

//    public function upload($id, Request $request)
//    {
//        $page = page::find($id);
//        $page->addFile($request->file('image'), $request->type, $request->get('locale'));
//
//        return redirect()->back();
//    }
}