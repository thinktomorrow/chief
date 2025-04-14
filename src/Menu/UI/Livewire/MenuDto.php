<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\TabItem;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuType;
use Thinktomorrow\Chief\Sites\ChiefSites;

class MenuDto implements TabItem, Wireable
{
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly string $typeTitle,
        public readonly ?string $title,
        public readonly array $locales,
        public array $activeSites,
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

    public static function empty(string $type): static
    {
        return new static(
            id: 'new-'.Str::random(),
            type: $type,
            typeTitle: MenuType::find($type)->getLabel(),
            title: null,
            locales: ChiefSites::locales(),
            activeSites: [],
        );
    }

    public function addActiveSites(array $activeSites): void
    {
        $this->activeSites = array_merge($this->activeSites, $activeSites);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAllowedSites(): array
    {
        return $this->locales;
    }

    public function getActiveSites(): array
    {
        return $this->activeSites;
    }

    public function exists(): bool
    {
        return ! Str::startsWith($this->id, 'new-');
    }
}
