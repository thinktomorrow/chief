<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Tree;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Vine\NodeCollection;

class PrepareMenuItemsForAdminSelect
{
    public function prepare(NodeCollection $items, ?MenuItem $model = null): Collection
    {
        $collection = $items;

        if ($model) {
            $collection = $collection->remove(function (MenuItem $node) use ($model) {
                return $node->getNodeId() == $model->id;
            });
        }

        $menu = $collection->mapRecursive(function (MenuItem $node) {
            $node->setLabel(
                ($node->getNodeDepth() != 0 ? (str_repeat('-', $node->getNodeDepth())).'> ' : '').
                $node->getAnyLabel(),
                app()->getLocale()
            );

            return $node;
        });

        $menuitems = collect();
        $menu->flatten()->each(function (MenuItem $node) use ($menuitems) {
            $menuitems[] = [
                'label' => $node->getAnyLabel(),
                'value' => $node->getNodeId(),
            ];
        });

        return $menuitems;
    }
}
