<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Tree;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Vine\NodeCollection;

class PrepareMenuItemsForAdminSelect
{
    public function prepare(NodeCollection $items, MenuItem $model = null): Collection
    {
        $this->collection = $items;

        if ($model) {
            $this->collection = $this->collection->prune(function ($node) use ($model) {
                return ! in_array($model->id, $node->pluckAncestors('id'));
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
            $menuitems[] = [
                'label' => $node->label,
                'id' => $node->id,
            ];
        });

        return $menuitems;
    }
}
