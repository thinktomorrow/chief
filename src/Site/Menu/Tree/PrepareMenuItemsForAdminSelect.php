<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Tree;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Vine\NodeCollection;

class PrepareMenuItemsForAdminSelect
{
    public function prepare(NodeCollection $items, MenuItem $model = null): Collection
    {
        $this->collection = $items;

        if ($model) {
            $this->collection = $this->collection->prune(function (MenuItemNode $node) use ($model) {
                return ! in_array($model->id, $node->pluckAncestorNodes('id'));
            });
        }

        $menu = $this->collection->mapRecursive(function (MenuItemNode $node) {
            $node->setLabel(
                ($node->getNodeDepth() != 0 ? (str_repeat('-', $node->getNodeDepth())) . '>' : '') .
                $node->getLabel());

            return $node;
        });

        $menuitems = collect();
        $menu->flatten()->each(function (MenuItemNode $node) use ($menuitems) {
            $menuitems[] = [
                'label' => $node->getLabel(),
                'id' => $node->getId(),
            ];
        });

        return $menuitems;
    }
}
