<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

use Illuminate\Support\Collection;

class MenuType
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

        return collect($types)->map(function ($label, $key) {

            if (! is_string($label)) {
                throw new \InvalidArgumentException('Menu type label must be a string, '.gettype($label)." given for key: {$key}");
            }

            return new static($key, $label);
        });
    }

    public static function find($key): ?self
    {
        return static::all()->filter(function (self $menuType) use ($key) {
            return $menuType->key == $key;
        })->first();
    }

    public function getType(): string
    {
        return $this->key;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
