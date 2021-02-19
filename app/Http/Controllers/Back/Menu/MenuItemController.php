<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Menu;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Site\Menu\Application\CreateMenu;
use Thinktomorrow\Chief\Site\Menu\Application\DeleteMenu;
use Thinktomorrow\Chief\Site\Menu\Application\UpdateMenu;
use Thinktomorrow\Chief\Site\Menu\ChiefMenu;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Site\Menu\Tree\PrepareMenuItemsForAdminSelect;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;

class MenuItemController extends Controller
{
    /** @var PrepareMenuItemsForAdminSelect */
    private PrepareMenuItemsForAdminSelect $prepareMenuItemsForAdminSelect;

    public function __construct(PrepareMenuItemsForAdminSelect $prepareMenuItemsForAdminSelect)
    {
        $this->prepareMenuItemsForAdminSelect = $prepareMenuItemsForAdminSelect;
    }

    public function create(string $menutype)
    {
        $this->authorize('create-page');

        $menuitem = new MenuItem();
        $menuitem->type = MenuItem::TYPE_INTERNAL;  // Default menu type
        $menuitem->menu_type = $menutype;

        $menuitems = $this->prepareMenuItemsForAdminSelect->prepare(
            ChiefMenu::fromMenuItems($menuitem->menuType())->items()
        );

        return view('chief::back.menu.create', [
            'pages' => UrlHelper::allOnlineModels(),
            'menuitem' => $menuitem,
            'ownerReference' => null,
            'parents' => $menuitems,
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

        $menuitems = $this->prepareMenuItemsForAdminSelect->prepare(
            ChiefMenu::fromMenuItems($menuitem->menuType())->items(),
            $menuitem
        );

        return view('chief::back.menu.edit', [
            'menuitem' => $menuitem,
            'pages' => UrlHelper::allOnlineModels(),
            'ownerReference' => $menuitem->owner ? $menuitem->owner->modelReference()->get() : null,
            'parents' => $menuitems,
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
