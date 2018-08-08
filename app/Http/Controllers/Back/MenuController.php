<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Common\Collections\CollectionDetails;
use Thinktomorrow\Chief\Common\Collections\CollectionKeys;
use Thinktomorrow\Chief\Common\FlatReferences\FlatReferencePresenter;
use Thinktomorrow\Chief\Menu\Application\CreateMenu;
use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Menu\Application\UpdateMenu;
use Thinktomorrow\Chief\Menu\Application\DeleteMenu;

class MenuController extends Controller
{
    public function index()
    {
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
        $menuitem       = new MenuItem;
        $menuitem->type = MenuItem::TYPE_INTERNAL; // Default menu type
        
        $menuitems = ChiefMenu::fromMenuItems()->getForSelect();

        $collections = CollectionKeys::fromConfig()->filterByType('pages')->rejectByKey('singles')->toCollectionDetails()->values()->toArray();

        return view('chief::back.menu.create', [
            'pages'            => FlatReferencePresenter::toGroupedSelectValues(Page::all())->toArray(),
            'menuitem'         => $menuitem,
            'collections'      => $collections,
            'internal_page_id' => null,
            'parents'          => $menuitems,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MenuRequest $request
     * @return Response
     */
    public function store(MenuRequest $request)
    {
        $menu = app(CreateMenu::class)->handle($request);

        return redirect()->route('chief.back.menu.index')->with('messages.success', $menu->label . ' is aangemaakt');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $menuitem = MenuItem::findOrFail($id);
        $menuitem->injectTranslationForForm();

        // Transpose selected page_id to the format <class>@<id>
        // as expected by t9he select field.
        $internal_page_id = null;
        if ($menuitem->type == MenuItem::TYPE_INTERNAL && $menuitem->page_id) {
            $page = Page::find($menuitem->page_id);
            $internal_page_id = $page->flatReference()->get();
        }

        $menuitems = ChiefMenu::fromMenuItems()->getForSelect($id);
        $collections = CollectionKeys::fromConfig()->filterByType('pages')->rejectByKey('singles')->toCollectionDetails()->values()->toArray();

        $pages = FlatReferencePresenter::toGroupedSelectValues(Page::all()->reject(function ($page) {
            return $page->hidden_in_menu == true;
        }))->toArray();

        return view('chief::back.menu.edit', [
            'menuitem'         => $menuitem,
            'pages'            => $pages,
            'collections'      => $collections,
            'internal_page_id' => $internal_page_id,
            'parents'          => $menuitems,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MenuRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(MenuRequest $request, $id)
    {
        $menu = app(UpdateMenu::class)->handle($id, $request);

        return redirect()->route('chief.back.menu.index')->with('messages.success', $menu->label .' is aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $menu = app(DeleteMenu::class)->handle($id);

        if ($menu) {
            $message = 'Het item werd verwijderd.';

            return redirect()->route('chief.back.menu.index')->with('messages.warning', $message);
        } else {
            return redirect()->back()->with('messages.warning', 'Je menu item is niet verwijderd. Probeer opnieuw');
        }
    }
}
