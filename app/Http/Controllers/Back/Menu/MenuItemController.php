<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Menu;

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

class MenuItemController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($menutype)
    {
        $menuitem            = new MenuItem;
        $menuitem->type      = MenuItem::TYPE_INTERNAL;  // Default menu type
        $menuitem->menu_type = $menutype;

        $menuitems = ChiefMenu::fromMenuItems($menuitem->menuType())->getForSelect();

        $collections = CollectionKeys::fromConfig()
            ->filterByType('pages')
            ->rejectByKey('singles')
            ->toCollectionDetails()
            ->values()
            ->prepend([
                'key' => null,
                'plural' => '...',
            ])->toArray();

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

        return redirect()->route('chief.back.menus.show', $menu->menu_type)->with('messages.success', $menu->label . ' is aangemaakt');
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
            //Archived and deleted pages can no longer be referenced in a menu item
            if ($page = Page::find($menuitem->page_id)) {
                $internal_page_id = $page->flatReference()->get();
            }
        }

        $menuitems   = ChiefMenu::fromMenuItems($menuitem->menuType())->getForSelect($id);
        $collections = CollectionKeys::fromConfig()
                                ->filterByType('pages')
                                ->rejectByKey('singles')
                                ->toCollectionDetails()
                                ->values()
                                ->prepend([
                                    'key' => null,
                                    'plural' => '...',
                                ])->toArray();

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

        return redirect()->route('chief.back.menus.index')->with('messages.success', $menu->label .' is aangepast');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $menuItem = app(DeleteMenu::class)->handle($id);

        if ($menuItem) {
            $message = 'Het item werd verwijderd.';

            return redirect()->route('chief.back.menus.show', $menuItem->menuType())->with('messages.warning', $message);
        } else {
            return redirect()->back()->with('messages.warning', 'Je menu item is niet verwijderd. Probeer opnieuw');
        }
    }
}
