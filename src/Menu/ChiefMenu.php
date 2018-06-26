<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Menu;

use Vine\NodeCollection;
use Vine\Node;
use Thinktomorrow\Chief\Pages\Page;

class ChiefMenu
{
    private $collection;

    public function __construct(NodeCollection $collection)
    {
        $this->collection = $collection;
    }

    public static function fromMenuItems()
    {
        $collection = NodeCollection::fromSource(new MenuItem());

        return new static($collection);
    }

    public static function fromArray(array $items)
    {
        $collection = NodeCollection::fromArray($items);

        return new static($collection);
    }

    public function items(): NodeCollection
    {
        return $this->collection->prune(function (Node $node) {
            $page = Page::ignoreCollection()->find($node->page_id);
            if(!$page)
            {
                return true;
            } 
            else{
                return $page->hidden_in_menu == false;
            }
        })->sort('order');
    }
}
