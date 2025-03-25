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
        public readonly string $title,
        public readonly array $locales,
    ) {
        //
    }

    public static function fromModel(Menu $menu): self
    {
        return new static(
            id: $menu->id,
            title: $menu->title,
            locales: $menu->sites,
        );
    }

    public static function makeDefault(string $type, int $order): self
    {
        return new static(
            id: 'new-'.Str::random(),
            title: MenuType::find($type)->getLabel().' #'.$order,
            locales: ChiefLocales::locales(),
        );
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'locales' => $this->locales,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            id: $value['id'],
            title: $value['title'],
            locales: $value['locales'],
        );
    }
}
