<?php

namespace Thinktomorrow\Chief\Menu\App\Queries;

use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Vine\NodeCollection;

class MenuTree
{
    /**
     * Get the menu tree for a specific site and type.
     * The offline items are removed from the collection.
     */
    public static function bySite(string $site, string $type): NodeCollection
    {
        $menu = Menu::bySiteLocale($site)->where('type', $type)->first();

        if (! $menu) {
            return new NodeCollection;
        }

        return static::byMenu((string) $menu->id)
            ->remove(fn ($menuItem) => $menuItem->isOffline());
    }

    public static function byMenu(string $menuId): NodeCollection
    {
        return NodeCollection::fromIterable(
            MenuItem::where('menu_id', $menuId)->get()
        )->sort('order');
    }
}
