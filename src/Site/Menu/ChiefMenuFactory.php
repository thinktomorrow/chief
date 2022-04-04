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

    // Faster build for frontend coming from projected data...
    public function forSite(string $key, string $locale): self
    {
        // Include hidden : true;
    }

    // Slower build for admin but with full record data included.
    public function forAdmin(string $key, string $locale): NodeCollection
    {
        // Include hidden : false;
        //trap(MenuItem::where('menu_type', $key)->whereRaw('LOWER(json_extract(`values`, "$.label.en")) = ?', 'null')->get());
        return $this->nodeCollectionFactory->fromSource(
            MenuSource::fromCollection(MenuItem::where('menu_type', $key)->get(), $locale)
        )->sort('order');
    }

//    public static function empty(): self
//    {
//        return new static(new NodeCollection());
//    }

//    /**
//     * @return static
//     */
//    public function includeHidden(): self
//    {
//        // TODO: THIS DOES NOT WORK???
//        $this->includeHidden = true;
//
//        return $this;
//    }

//    public function items(): NodeCollection
//    {
//        return $this->collection;
//    }

//    private function sanitize(NodeCollection $collection): NodeCollection
//    {
//        if (! $this->includeHidden) {
//            $collection = $collection->shake(function (MenuItemNode $node) {
//                return ! $node->isHiddenInMenu() && ! $node->isDraft();
//            });
//        }
//
//        return $collection->sort('order');
//    }
}
