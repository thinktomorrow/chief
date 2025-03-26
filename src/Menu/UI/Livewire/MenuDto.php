<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuType;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;

class MenuDto implements Wireable
{
    public function __construct(
        public readonly string $id,
        public readonly string $type,
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
            title: $menu->title,
            locales: $menu->sites,
            activeSites: $menu->getActiveSiteLocales(),
        );
    }

    public static function makeDefault(string $type, int $order): self
    {
        return new static(
            id: 'new-'.Str::random(),
            type: $type,
            title: MenuType::find($type)->getLabel().' #'.$order,
            locales: ChiefLocales::locales(),
            activeSites: [],
        );
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
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
