<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Nav;

final class Nav
{
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function add(NavItem $navItem): self
    {
        $this->items = array_merge($this->items, [$navItem]);

        return $this;
    }

    public function tagged($tags): self
    {
        $filteredItems = array_filter($this->items, function (NavItem $navItem) use ($tags) {
            return $navItem->isTagged($tags);
        });

        return static::fromItems($filteredItems);
    }

    public function untagged(): self
    {
        $filteredItems = array_filter($this->items, function (NavItem $navItem) {
            return $navItem->isUntagged();
        });

        return static::fromItems($filteredItems);
    }

    public static function fromItems(array $navItems): self
    {
        $instance = new static();

        foreach ($navItems as $item) {
            $instance->add($item);
        }

        return $instance;
    }

    public function all(): array
    {
        return $this->items;
    }
}
