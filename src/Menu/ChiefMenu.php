<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Menu;

use Vine\NodeCollection;
use Thinktomorrow\Chief\Menu\Tree\MenuTreeRepositoryContract;

class ChiefMenu
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = app(MenuTreeRepositoryContract::class)->get();
    }

    public function items(): NodeCollection
    {
        return $this->items;
    }
}