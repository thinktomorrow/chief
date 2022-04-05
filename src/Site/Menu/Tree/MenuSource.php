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
        if (! $entry instanceof MenuItem) {
            throw new \InvalidArgumentException('Entry argument should be instance of '.MenuItem::class);
        }

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
}
