<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Common\Relations\RelatedCollection;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\PageCreateRequest;
use Thinktomorrow\Chief\Pages\Application\UpdatePage;
use Thinktomorrow\Chief\App\Http\Requests\PageUpdateRequest;

class PagesController extends Controller
{
    public function index()
    {
        $published  = Page::unarchived()->published()->paginate(10);
        $drafts     = Page::unarchived()->where('published', 0)->paginate(10);
        $archived   = Page::archived()->paginate(10);

        return view('chief::back.pages.index', compact('published', 'drafts', 'archived'));
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

        return view('chief::back.pages.create',['page' => $page, 'relations' => $relations]);
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

        return redirect()->route('chief.back.pages.index')->with('messages.success', $page->title .' is aangemaakt');
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

        return view('chief::back.pages.edit', compact('page', 'relations'));
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

        return redirect()->route('chief.back.pages.index')->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "'.$page->title .'" werd aangepast');
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
        if(request()->get('deleteconfirmation') !== 'DELETE' && (!$page->isPublished() || $page->isArchived())) return redirect()->back()->with('messages.warning', 'fout');

        if($page->isDraft() || $page->isArchived()) $page->delete();
        if($page->isPublished()) $page->archive();

        $message = 'Het item werd verwijderd.';

        return redirect()->route('chief.back.pages.index')->with('messages.warning', $message);
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