<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Menu;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Menu\Application\CreateMenu;
use Thinktomorrow\Chief\Menu\Application\DeleteMenu;
use Thinktomorrow\Chief\Menu\Application\UpdateMenu;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class MenuItemController extends Controller
{
    public function create($menutype)
    {
        $this->authorize('create-page');

        $menuitem            = new MenuItem;
        $menuitem->type      = MenuItem::TYPE_INTERNAL;  // Default menu type
        $menuitem->menu_type = $menutype;

        $menuitems = ChiefMenu::fromMenuItems($menuitem->menuType())->getForSelect();

        return view('chief::back.menu.create', [
            'pages'            => FlatReferencePresenter::toGroupedSelectValues(Page::all())->toArray(),
            'menuitem'         => $menuitem,
            'internal_page_id' => null,
            'parents'          => $menuitems,
        ]);
    }

    public function store(MenuRequest $request)
    {
        $this->authorize('create-page');

        $menu = app(CreateMenu::class)->handle($request);

        Audit::activity()
            ->performedOn($menu)
            ->log('created');

        return redirect()->route('chief.back.menus.show', $menu->menu_type)->with('messages.success', $menu->label . ' is aangemaakt');
    }

    public function edit($id)
    {
        $this->authorize('update-page');

        $menuitem = MenuItem::findOrFail($id);
        $menuitem->injectTranslationForForm();

        // Transpose selected page_id to the format <class>@<id>
        // as expected by the select field.
        $internal_page_id = null;
        if ($menuitem->type == MenuItem::TYPE_INTERNAL && $menuitem->page_id) {
            //Archived and deleted pages can no longer be referenced in a menu item
            if ($page = Page::find($menuitem->page_id)) {
                $internal_page_id = $page->flatReference()->get();
            }
        }

        $menuitems   = ChiefMenu::fromMenuItems($menuitem->menuType())->getForSelect($id);

        $pages = FlatReferencePresenter::toGroupedSelectValues(Page::all()->reject(function ($page) {
            return $page->hidden_in_menu == true;
        }))->toArray();


        return view('chief::back.menu.edit', [
            'menuitem'         => $menuitem,
            'pages'            => $pages,
            'internal_page_id' => $internal_page_id,
            'parents'          => $menuitems,
        ]);
    }

    public function update(MenuRequest $request, $id)
    {
        $this->authorize('update-page');

        $menu = app(UpdateMenu::class)->handle($id, $request);

        Audit::activity()
            ->performedOn($menu)
            ->log('updated');

        return redirect()->route('chief.back.menus.show', $menu->menu_type)->with('messages.success', $menu->label . ' is aangepast');
    }

    public function destroy($id)
    {
        $this->authorize('delete-page');

        $menuItem = app(DeleteMenu::class)->handle($id);

        if ($menuItem) {
            $message = 'Het item werd verwijderd.';

            Audit::activity()
                ->performedOn($menuItem)
                ->log('deleted');

            return redirect()->route('chief.back.menus.show', $menuItem->menuType())->with('messages.warning', $message);
        } else {
            return redirect()->back()->with('messages.warning', 'Je menu item is niet verwijderd. Probeer opnieuw');
        }
    }
}
