<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Thinktomorrow\Chief\Site\Menu\Tree\MenuItemNode;
use Thinktomorrow\Chief\Site\Menu\Tree\MenuSource;
use Thinktomorrow\Vine\NodeCollection;
use Thinktomorrow\Vine\NodeCollectionFactory;

class ChiefMenuFactory
{
    private NodeCollectionFactory $nodeCollectionFactory;

    private static $loaded = [];

    public function __construct(NodeCollectionFactory $nodeCollectionFactory)
    {
        $this->nodeCollectionFactory = $nodeCollectionFactory;
    }

    /**
     * Build the menu tree excluding offline items. We also memoize the output
     * to reduce redundant retrieval of menu records.
     */
    public function forSite(string $key, string $locale): NodeCollection
    {
        if(isset(static::$loaded[$cacheKey = $key . '_' . $locale])) {
            return static::$loaded[$cacheKey];
        }

        return static::$loaded[$cacheKey] = $this->nodeCollectionFactory->fromSource(
            MenuSource::fromCollection(MenuItem::where('menu_type', $key)->get(), $locale)
        )->remove(fn (MenuItemNode $node) => $node->isOffline())
            ->sort('order')
        ;
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
        return $this->nodeCollectionFactory->fromSource(
            MenuSource::fromCollection(MenuItem::where('menu_type', $key)->get(), $locale)
        )->sort('order');
    }
}
