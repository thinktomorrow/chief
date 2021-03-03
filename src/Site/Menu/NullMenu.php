<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Illuminate\Support\Collection;
use Vine\NodeCollection;

class NullMenu
{
    public static function all(): Collection
    {
        return collect([]);
    }

    public static function find($key): ?self
    {
        return null;
    }

    public function key()
    {
        return null;
    }

    public function label()
    {
        return null;
    }

    public function viewPath()
    {
        return null;
    }

    public function menu(): ChiefMenu
    {
        return new ChiefMenu(new NodeCollection());
    }

    public function items(): NodeCollection
    {
        return $this->menu()->items();
    }

    public function render(): string
    {
        return '';
    }
}
