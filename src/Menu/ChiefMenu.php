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
        return $this->collection->sort('order');
    }

    public function getForSelect($id = null)
    {
        $this->collection = $this->items();
        
        if($id){
            $this->collection = $this->collection->prune(function($node) use($id){
                return !in_array($id, $node->pluckAncestors('id'));
            });
        }

        $menu = $this->collection->mapRecursive(function($node){
            $entry = $node->entry();
            $label = $entry->label;
            $entry->label = $node->depth() != 0 ? (str_repeat('-', $node->depth())) . '>' : '';
            $entry->label .= $label;
            return $node->replaceEntry($entry);
        });

        $menuitems = collect();
        $menu->flatten()->each(function($node) use($menuitems){
            $menuitems[]  = [
                'label' => $node->label,
                'id'    => $node->id
            ];
        });

        return $menuitems;
    }
}
