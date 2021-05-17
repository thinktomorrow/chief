<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Thinktomorrow\Chief\Site\Menu\Tree\BuildMenuItemsTree;
use Vine\NodeCollection;

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
        return new static(app(BuildMenuItemsTree::class)->build(MenuItem::where('menu_type', $type)->get()));
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
            $collection = $collection->shake(function ($node) {
                return ! $node->hidden_in_menu && ! $node->draft;
            });
        }

        return $collection->sort('order');
    }
}
