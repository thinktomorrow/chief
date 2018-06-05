<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\MenuCreateRequest;
use Thinktomorrow\Chief\Menu\Application\CreateMenu;
use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Menu\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        //Demo menu items
        // $first  = MenuItem::create(['label:en' => 'first item']);
        // $second = MenuItem::create(['label:en' => 'second item', 'parent_id' => $first->id, 'order' => 2]);
        // $third  = MenuItem::create(['label:en' => 'last item', 'parent_id' => $first->id, 'order' => 1, 'hidden_in_menu' => 1]);
        
        $menu = ChiefMenu::fromMenuItems()->items();

        return view('chief::back.menu.index', compact('menu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('chief::back.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(MenuCreateRequest $request)
    {
        $menu = app(CreateMenu::class)->handle($request);

        return redirect()->route('chief.back.pages.index')->with('messages.success', $menu->title . ' is aangemaakt');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $page = Page::ignoreCollection()->findOrFail($id);
        $page->injectTranslationForForm();

        $page->existingRelationIds = RelatedCollection::relationIds($page->children());
        $relations = RelatedCollection::availableChildren($page)->flattenForGroupedSelect()->toArray();

        return view('chief::back.pages.edit', [
            'page'            => $page,
            'relations'       => $relations
        ]);
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

        return redirect()->route('chief.back.pages.index', $page->collectionKey())->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $page->title . '" werd aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $page = Page::ignoreCollection()->findOrFail($id);
        if (request()->get('deleteconfirmation') !== 'DELETE' && (!$page->isPublished() || $page->isArchived()))
        {
            return redirect()->back()->with('messages.warning', 'fout');
        }

        if ($page->isDraft() || $page->isArchived()) {
            $page->delete();
        }
        if ($page->isPublished()) {
            $page->archive();
        }

        $message = 'Het item werd verwijderd.';

        return redirect()->route('chief.back.pages.index', $page->collectionKey())->with('messages.warning', $message);
    }
}
