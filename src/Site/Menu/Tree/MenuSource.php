<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Tree;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Vine\Node;
use Thinktomorrow\Vine\Source;

class MenuSource implements Source
{
    private Collection $items;
    private string $locale;

    private function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public static function fromCollection(Collection $items, string $locale): static
    {
        $instance = new static($items);
        $instance->setLocale($locale);

        return $instance;
    }

    public function nodeEntries(): iterable
    {
        return $this->items;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function createNode($entry): Node
    {
        if (!$entry instanceof MenuItem) {
            throw new \InvalidArgumentException('Entry argument should be instance of '.MenuItem::class);
        }

        // GET PROJECTED LINKS BY LOCALE

        // MenuItemStatus $status, string $id, ?string $parentId, int $order, string $label, string $url
        return new MenuItemNode(
            $entry->getStatus(),
            $entry->getLabel($this->locale) ?: $entry->getAdminUrlLabel($this->locale),
            $entry->getUrl($this->locale),
            $entry->getAdminUrlLabel($this->locale),
            (string) $entry->id,
            (string) $entry->parent_id,
            $entry->order,
        );
    }

    private function filter(Collection $items): Collection
    {
        // TODO: this should be obsolute... and handled by events

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
