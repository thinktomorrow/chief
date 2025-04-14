<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuType;

class MenuDto implements Wireable
{
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly string $typeTitle,
        public readonly string $title,
        public readonly array $locales,
        public readonly array $activeSites,
    ) {
        //
    }

    public static function fromModel(Menu $menu): self
    {
        return new static(
            id: $menu->id,
            type: $menu->type,
            typeTitle: MenuType::find($menu->type)->getLabel(),
            title: $menu->title,
            locales: $menu->getAllowedSites(),
            activeSites: $menu->getActiveSites(),
        );
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'typeTitle' => $this->typeTitle,
            'title' => $this->title,
            'locales' => $this->locales,
            'activeSites' => $this->activeSites,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            id: $value['id'],
            type: $value['type'],
            typeTitle: $value['typeTitle'],
            title: $value['title'],
            locales: $value['locales'],
            activeSites: $value['activeSites'],
        );
    }

    public function hasActiveSite(string $site): bool
    {
        return in_array($site, $this->activeSites);
    }
}
