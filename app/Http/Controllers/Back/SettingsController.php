<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Menu\Application\CreateMenu;
use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Menu\Application\UpdateMenu;
use Thinktomorrow\Chief\Menu\Application\DeleteMenu;

class SettingsController extends Controller
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
        $menuitem       = new MenuItem;
        $menuitem->type = MenuItem::TYPE_INTERNAL; // Default menu type

        $menuitems = ChiefMenu::fromMenuItems()->getForSelect();

        return view('chief::back.menu.create', [
            'pages'            => Page::flattenForGroupedSelect()->toArray(),
            'menuitem'         => $menuitem,
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

        return redirect()->route('chief.back.menu.index')->with('messages.success', $menu->label . ' is aangemaakt');
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
            $page = Page::ignoreCollection()->find($menuitem->page_id);
            $internal_page_id = $page->getRelationId();
        }

        $menuitems = ChiefMenu::fromMenuItems()->getForSelect($id);

        return view('chief::back.menu.edit', [
            'menuitem'         => $menuitem,
            'pages'            => $pages = Page::flattenForGroupedSelect()->toArray(),
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

        if ($menu) {
            $message = 'Het item werd verwijderd.';

            return redirect()->route('chief.back.menu.index')->with('messages.warning', $message);
        } else {
            return redirect()->back()->with('messages.warning', 'Je menu item is niet verwijderd. Probeer opnieuw');
        }
    }
}
