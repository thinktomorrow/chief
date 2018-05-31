<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Menu;

use Vine\NodeCollection;

class ChiefMenu
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = new NodeCollection($items);
    }

    public function items(): NodeCollection
    {
        return $this->items;
    }
}