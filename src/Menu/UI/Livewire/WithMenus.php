<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Menu\Menu;

trait WithMenus
{
    public function getMenus(): Collection
    {
        return Menu::where('type', $this->type)->get()->map(fn ($menu) => MenuDto::fromModel($menu));
    }
}
