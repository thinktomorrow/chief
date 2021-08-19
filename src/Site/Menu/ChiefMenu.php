<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Thinktomorrow\Chief\Site\Menu\Tree\MenuItemNode;
use Thinktomorrow\Chief\Site\Menu\Tree\MenuSource;
use Thinktomorrow\Vine\NodeCollection;
use Thinktomorrow\Vine\NodeCollectionFactory;

class ChiefMenu
{
    private NodeCollection $collection;
    private bool $includeHidden = false;

    final private function __construct(NodeCollection $collection)
    {
        $this->collection = $this->sanitize($collection);
    }

    /**
     * @return static
     */
    public static function fromMenuItems(string $type = 'main'): self
    {
        $nodeCollection = app()->make(NodeCollectionFactory::class)->fromSource(
            new MenuSource(MenuItem::where('menu_type', $type)->get())
        );

        return new static($nodeCollection);
    }

    public static function empty(): self
    {
        return new static(new NodeCollection());
    }

    /**
     * @return static
     */
    public function includeHidden(): self
    {
        $this->includeHidden = true;

        return $this;
    }

    public function items(): NodeCollection
    {
        return $this->collection;
    }

    private function sanitize(NodeCollection $collection): NodeCollection
    {
        if (! $this->includeHidden) {
            $collection = $collection->shake(function (MenuItemNode $node) {
                return ! $node->isHiddenInMenu() && ! $node->isDraft();
            });
        }

        return $collection->sort('order');
    }
}
