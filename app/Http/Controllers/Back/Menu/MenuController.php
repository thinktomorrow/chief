<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Menu;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Site\Menu\ChiefMenu;
use Thinktomorrow\Chief\Site\Menu\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $this->authorize('view-page');

        $menus = Menu::all();

        // If there is only one menu, we will show the menu immediately.
        if ($menus->count() == 1) {
            return $this->show($menus->first()->key());
        }

        return view('chief::back.menu.index', [
            'menus' => $menus
        ]);
    }

    public function show($type)
    {
        $this->authorize('view-page');

        $menu = Menu::find($type);

        return view('chief::back.menu.show', [
            'menuItems' => ChiefMenu::fromMenuItems($type)->includeHidden()->items(),
            'menu' => $menu,
        ]);
    }
}
