<?php

namespace Thinktomorrow\Chief\Menu\App\Controllers;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Menu\App\Actions\CreateMenuItem;
use Thinktomorrow\Chief\Menu\App\Actions\DeleteMenuItem;
use Thinktomorrow\Chief\Menu\App\Actions\MenuItemApplication;
use Thinktomorrow\Chief\Menu\App\Actions\UpdateMenuItem;
use Thinktomorrow\Chief\Menu\App\Queries\MenuTree;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\MenuLinkType;
use Thinktomorrow\Chief\Menu\Tree\PrepareMenuItemsForAdminSelect;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlHelper;

class MenuItemController extends Controller
{
    private PrepareMenuItemsForAdminSelect $prepareMenuItemsForAdminSelect;

    private MenuItemApplication $menuItemApplication;

    public function __construct(MenuItemApplication $menuItemApplication, PrepareMenuItemsForAdminSelect $prepareMenuItemsForAdminSelect)
    {
        $this->prepareMenuItemsForAdminSelect = $prepareMenuItemsForAdminSelect;
        $this->menuItemApplication = $menuItemApplication;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(string $id)
    {
        $this->authorize('create-page');

        $menuitem = new MenuItem;
        $menuitem->menu_id = $id;
        $menuitem->type = MenuLinkType::internal->value;  // Default menu type

        $menuitems = $this->prepareMenuItemsForAdminSelect->prepare(
            MenuTree::byMenu($id),
        );

        return view('chief-menu::create', [
            'menu' => Menu::findOrFail($id),
            'menuitem' => $menuitem,
            'pages' => UrlHelper::allOnlineModels(),
            'ownerReference' => null,
            'parents' => $menuitems,
        ]);
    }

    public function store($id, MenuRequest $request)
    {
        $this->authorize('create-page');

        $menu = Menu::findOrFail($id);

        $menuItemId = app(MenuItemApplication::class)->create(CreateMenuItem::fromRequest($id, $request));

        return redirect()->route('chief.back.menus.show', [$menu->type, $menu->id])->with('messages.success', 'Menu item is aangemaakt');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->authorize('update-page');

        $menuitem = MenuItem::findOrFail($id);

        $menuitems = $this->prepareMenuItemsForAdminSelect->prepare(
            MenuTree::byMenu($menuitem->menu_id),
            $menuitem
        );

        return view('chief-menu::edit', [
            'menu' => $menuitem->menu,
            'menuitem' => $menuitem,
            'pages' => UrlHelper::allOnlineModels(),
            'ownerReference' => $menuitem->owner ? $menuitem->owner->modelReference()->getShort() : null,
            'parents' => $menuitems,
        ]);
    }

    public function update(MenuRequest $request, $id)
    {
        $this->authorize('update-page');

        $menuItem = MenuItem::findOrFail($id);

        $this->menuItemApplication->update(UpdateMenuItem::fromRequest($id, $request));

        return redirect()->route('chief.back.menus.show', [$menuItem->menu->type, $menuItem->menu_id])->with('messages.success', 'Menu item is aangepast');
    }

    public function destroy($id)
    {
        $this->authorize('delete-page');

        $menuItem = MenuItem::findOrFail($id);

        $this->menuItemApplication->delete(new DeleteMenuItem($id));

        return redirect()->route('chief.back.menus.show', [$menuItem->menu->type, $menuItem->menu_id])->with('messages.warning', 'Menu item is verwijderd');
    }
}
