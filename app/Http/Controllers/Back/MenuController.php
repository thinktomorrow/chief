<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\MenuCreateRequest;
use Thinktomorrow\Chief\Menu\Application\CreateMenu;
use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Menu\Application\UpdateMenu;
use Thinktomorrow\Chief\App\Http\Requests\MenuUpdateRequest;
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
        $menuitem = new MenuItem;
        $pages = Page::flattenForGroupedSelect()->toArray();
        
        return view('chief::back.menu.create', compact('pages', 'menuitem'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(MenuCreateRequest $request)
    {
        $menu = app(CreateMenu::class)->handle($request);

        return redirect()->route('chief.back.menu.index')->with('messages.success', $menu->title . ' is aangemaakt');
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

        return view('chief::back.menu.edit', [
            'menuitem' => $menuitem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function update(MenuUpdateRequest $request, $id)
    {
        $menu = app(UpdateMenu::class)->handle($id, $request);

        return redirect()->route('chief.back.menu.index')->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  "' . $menu->title . '" werd aangepast');
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

        if($menu){
            $message = 'Het item werd verwijderd.';
        return redirect()->route('chief.back.menu.index')->with('messages.warning', $message);
        }else{
            return redirect()->back()->with('messages.warning', 'Je menu item is niet verwijderd. Probeer opnieuw');
        }

    }
}
