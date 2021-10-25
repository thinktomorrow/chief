<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Tree;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Vine\Node;
use Thinktomorrow\Vine\Source;

class MenuSource implements Source
{
    /** @var Collection */
    private Collection $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function nodeEntries(): iterable
    {
        return $this->filter($this->items);
    }

    public function createNode($entry): Node
    {
        if (! $entry instanceof MenuItem) {
            throw new \InvalidArgumentException('Entry argument should be instance of ' . MenuItem::class);
        }

        return new MenuItemNode($entry);
    }

    private function filter(Collection $items): Collection
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
                    $item->page_label = $item->label ?: '-';

                    $item->hidden_in_menu = $owner->hidden_in_menu;
                    $item->draft = (public_method_exists($owner, 'isDraft') && $owner->isDraft());
                    $items[$k] = $item;
                }
            }
        }

        return collect(array_merge($items->all(), $collectionItems->all()));
    }
}
