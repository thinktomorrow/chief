<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Illuminate\Support\Collection;
use Thinktomorrow\Vine\NodeCollection;

class Menu
{
    private string $key;
    private string $label;

    final public function __construct(string $key, string $label)
    {
        $this->label = $label;
        $this->key = $key;
    }

    public static function all(): Collection
    {
        $types = config('chief.menus', []);

        return collect($types)->map(function ($menu, $key) {
            return new static($key, $menu['label']);
        });
    }

    public static function find($key): ?self
    {
        return static::all()->filter(function ($menu) use ($key) {
            return $menu->key() == $key;
        })->first();
    }

    public function key(): string
    {
        return $this->key;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function items(): NodeCollection
    {
        return ChiefMenuFactory::build($this->key)->items();
    }
}
