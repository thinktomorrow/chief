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

    public function __construct(NodeCollectionFactory $nodeCollectionFactory)
    {
        $this->nodeCollectionFactory = $nodeCollectionFactory;
    }

    /**
     * Build the menu tree excluding offline items.
     */
    public function forSite(string $key, string $locale): NodeCollection
    {
        return $this->nodeCollectionFactory->fromSource(
            MenuSource::fromCollection(MenuItem::where('menu_type', $key)->get(), $locale)
        )->remove(fn (MenuItemNode $node) => $node->isOffline())
            ->sort('order')
        ;
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
