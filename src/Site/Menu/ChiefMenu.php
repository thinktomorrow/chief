<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Thinktomorrow\Chief\Site\Menu\Tree\BuildMenuItemsTree;
use Vine\NodeCollection;

class ChiefMenu
{
    private $collection;
    private $includeHidden = false;

    final private function __construct(NodeCollection $collection)
    {
        $this->collection = $this->sanitize($collection);
    }

    public static function fromMenuItems($type = 'main')
    {
        return new static(app(BuildMenuItemsTree::class)->build(
            MenuItem::where('menu_type', $type)->get()
        ));
    }

//    public static function fromArray(array $items)
//    {
//        $collection = NodeCollection::fromArray($items);
//
//        $collection->mapRecursive(function ($node) {
//            return $node->replaceEntry((new MenuItem())->entry($node));
//        });
//
//        return new static($collection);
//    }

    public function includeHidden()
    {
        $this->includeHidden = true;

        return $this;
    }

    public function items(): NodeCollection
    {
        return $this->collection;
    }

    public function sanitize(NodeCollection $collection): NodeCollection
    {
        if (! $this->includeHidden) {
            $collection = $collection->shake(function ($node) {
                return ! $node->hidden_in_menu && ! $node->draft;
            });
        }

        return $collection->sort('order');
    }
}
