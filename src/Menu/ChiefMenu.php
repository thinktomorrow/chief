<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Menu;

use Vine\NodeCollection;
use Vine\Node;
use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\DB;

class ChiefMenu
{
    private $collection;

    public function __construct(NodeCollection $collection)
    {
        $this->collection = $collection;
    }

    public static function forType($type = "main")
    {
        $items = MenuItem::where('menu_type', $type)->get();

        return self::fromArray($items->toArray());
    }

    public static function fromMenuItems($type = 'main')
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
        
        if ($id) {
            $this->collection = $this->collection->prune(function ($node) use ($id) {
                return !in_array($id, $node->pluckAncestors('id'));
            });
        }

        $menu = $this->collection->mapRecursive(function ($node) {
            $entry = $node->entry();
            $label = $entry->label;
            $entry->label = $node->depth() != 0 ? (str_repeat('-', $node->depth())) . '>' : '';
            $entry->label .= $label;
            return $node->replaceEntry($entry);
        });

        $menuitems = collect();
        $menu->flatten()->each(function ($node) use ($menuitems) {
            $menuitems[]  = [
                'label' => $node->label,
                'id'    => $node->id
            ];
        });

        return $menuitems;
    }

    public static function getTypes()
    {
        $types = DB::table('menu_items')->select('menu_type')->groupBy('menu_type')->get();

        return $types;
    }
}
