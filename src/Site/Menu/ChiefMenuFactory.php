<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Thinktomorrow\Chief\Site\Menu\Tree\MenuItemNode;
use Thinktomorrow\Vine\NodeCollection;

class ChiefMenuFactory
{
    private static array $loaded = [];

    /**
     * Build the menu tree excluding offline items. We also memoize the output
     * to reduce redundant retrieval of menu records.
     */
    public function forSite(string $key, string $locale): NodeCollection
    {
        if (isset(static::$loaded[$cacheKey = $key . '_' . $locale])) {
            return static::$loaded[$cacheKey];
        }

        return static::$loaded[$cacheKey] = $this->createCollection($key, $locale)
            ->remove(fn (MenuItemNode $node) => $node->isOffline())
            ->sort('order');
    }

    public static function clearLoaded(): void
    {
        static::$loaded = [];
    }

    /**
     * Build the entire menu tree including offline items.
     */
    public function forAdmin(string $key, string $locale): NodeCollection
    {
        return $this->createCollection($key, $locale);
    }

    private function createCollection(string $key, string $locale): NodeCollection
    {
        return NodeCollection::fromIterable(
            MenuItem::where('menu_type', $key)->get(),
            fn (MenuItem $menuItem) => MenuItemNode::fromModel($menuItem, $locale)
        )->sort('order');
    }
}
