<?php

namespace Thinktomorrow\Chief\Menu\App\Controllers;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Menu\MenuType;

class MenuController extends Controller
{
    public function index()
    {
        $this->authorize('view-page');

        $menuTypes = MenuType::all();

        // If there is only one menu, we will show the menu immediately.
        if ($menuTypes->count() == 1) {
            return $this->show($menuTypes->first()->getType());
        }

        return view('chief-menu::index', [
            'menuTypes' => $menuTypes,
        ]);
    }

    public function show(string $type, ?string $activeMenuId = null)
    {
        $this->authorize('view-page');

        return view('chief-menu::show', [
            'type' => $type,
            'typeLabel' => MenuType::find($type)->getLabel(),
            'activeMenuId' => $activeMenuId,
        ]);
    }
}
