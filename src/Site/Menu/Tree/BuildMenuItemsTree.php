<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Tree;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Vine\NodeCollection;

class BuildMenuItemsTree
{
    /**
     * Full array of original data rows
     * These are the rows to be converted to the tree model
     *
     * @param array $items
     * @return NodeCollection
     */
    public function build(Collection $items): NodeCollection
    {
        $collectionItems = collect([]);

        // Expose the collection items and populate them with the collection data
        foreach ($items as $k => $item) {
            if ($item->ofType(MenuItem::TYPE_INTERNAL) && $owner = $item->owner) {
                if (public_method_exists($owner, 'isArchived') && $owner->isArchived()) {
                    unset($items[$k]);
                } else {
                    $item->url = $item->url();

                    // Extra info on admin page.
                    $item->page_label = $owner instanceof ManagedModel ? $owner->adminLabel('title') : '-';

                    $item->hidden_in_menu = $owner->hidden_in_menu;
                    $item->draft = (public_method_exists($owner, 'isDraft') && $owner->isDraft());
                    $items[$k] = $item;
                }
            }
        }

        return $this->transformToNodeCollection(
            array_merge($items->all(), $collectionItems->all())
        );
    }

    private function transformToNodeCollection(array $items): NodeCollection
    {
        $collection = NodeCollection::fromArray($items);

        $collection->mapRecursive(function ($node) {
            return $node->replaceEntry((new MenuItem())->entry($node));
        });

        return $collection;
    }
}
