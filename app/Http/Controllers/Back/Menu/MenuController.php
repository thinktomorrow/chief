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

class MenuController extends Controller
{
    public function index()
    {
        $menu = ChiefMenu::getTypes();
        
        return view('chief::back.menu.index', compact('menu'));
    }

    public function show($type)
    {
        $menu = ChiefMenu::fromMenuItems($type)->items();

        return view('chief::back.menu.show', compact('menu'));
    }
}
