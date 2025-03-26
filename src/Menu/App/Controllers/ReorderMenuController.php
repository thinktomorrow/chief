<?php

namespace Thinktomorrow\Chief\Menu\App\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Menu\Events\MenuReordered;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Shared\Helpers\SortModels;

class ReorderMenuController
{
    public function index(string $id)
    {
        $menu = Menu::findOrFail($id);

        return view('chief-menu::reorder', [
            'menu' => $menu,
            'menuItems' => $menu->items,
        ]);
    }

    public function update(string $id, Request $request)
    {
        if (! $request->indices) {
            throw new \InvalidArgumentException('Missing arguments [indices] for sorting request.');
        }

        app(SortModels::class)->handle(
            'menu_items',
            $request->indices,
            'order',
            'id',
            'int',
        );

        event(new MenuReordered($id));

        return response()->json([
            'message' => 'menu reordered.',
        ]);
    }
}
