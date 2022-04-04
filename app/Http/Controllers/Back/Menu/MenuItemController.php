<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Menu;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Site\Menu\Application\CreateMenuItem;
use Thinktomorrow\Chief\Site\Menu\Application\DeleteMenuItem;
use Thinktomorrow\Chief\Site\Menu\Application\UpdateMenuItem;
use Thinktomorrow\Chief\Site\Menu\ChiefMenuFactory;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Site\Menu\Tree\PrepareMenuItemsForAdminSelect;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;

class MenuItemController extends Controller
{
    private PrepareMenuItemsForAdminSelect $prepareMenuItemsForAdminSelect;
    private ChiefMenuFactory $chiefMenuFactory;

    public function __construct(ChiefMenuFactory $chiefMenuFactory, PrepareMenuItemsForAdminSelect $prepareMenuItemsForAdminSelect)
    {
        $this->prepareMenuItemsForAdminSelect = $prepareMenuItemsForAdminSelect;
        $this->chiefMenuFactory = $chiefMenuFactory;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(string $menutype)
    {
        $this->authorize('create-page');

        $menuitem = new MenuItem();
        $menuitem->type = MenuItem::TYPE_INTERNAL;  // Default menu type
        $menuitem->menu_type = $menutype;

        $menuitems = $this->prepareMenuItemsForAdminSelect->prepare(
            $this->chiefMenuFactory->forAdmin($menutype, config('app.fallback_locale'))
        );

        return view('chief::admin.menu.create', [
            'pages' => UrlHelper::allOnlineModels(),
            'menuitem' => $menuitem,
            'ownerReference' => null,
            'parents' => $menuitems,
        ]);
    }

    public function store(MenuRequest $request)
    {
        $this->authorize('create-page');

        $menu = app(CreateMenuItem::class)->handle($request);

        Audit::activity()
            ->performedOn($menu)
            ->log('created');

        return redirect()->route('chief.back.menus.show', $menu->menu_type)->with('messages.success', $menu->label . ' is aangemaakt');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->authorize('update-page');

        $menuitem = MenuItem::findOrFail($id);

        $menuitems = $this->prepareMenuItemsForAdminSelect->prepare(
            $this->chiefMenuFactory->forAdmin(
            $menuitem->menuType(),
            config('app.fallback_locale')
        ),
            $menuitem
        );

        return view('chief::admin.menu.edit', [
            'menuitem' => $menuitem,
            'pages' => UrlHelper::allOnlineModels(),
            'ownerReference' => $menuitem->owner ? $menuitem->owner->modelReference()->getShort() : null,
            'parents' => $menuitems,
        ]);
    }

    public function update(MenuRequest $request, $id)
    {
        $this->authorize('update-page');

        $menu = app(UpdateMenuItem::class)->handle($id, $request);

        Audit::activity()
            ->performedOn($menu)
            ->log('updated');

        return redirect()->route('chief.back.menus.show', $menu->menu_type)->with('messages.success', $menu->label . ' is aangepast');
    }

    public function destroy($id)
    {
        $this->authorize('delete-page');

        $menuItem = app(DeleteMenuItem::class)->handle($id);

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
