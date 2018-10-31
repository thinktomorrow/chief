<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Menu;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Common\Morphable\CollectionDetails;
use Thinktomorrow\Chief\Common\Morphable\CollectionKeys;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;
use Thinktomorrow\Chief\Menu\Application\CreateMenu;
use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Menu\Application\UpdateMenu;
use Thinktomorrow\Chief\Menu\Application\DeleteMenu;

class MenuController extends Controller
{
    public function index()
    {
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
        $menu = Menu::find($type);

        return view('chief::back.menu.show', [
            'menuItems' => ChiefMenu::fromMenuItems($type)->items(),
            'menu' => $menu,
        ]);
    }
}
