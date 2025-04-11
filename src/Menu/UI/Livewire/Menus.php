<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\Items;
use Thinktomorrow\Chief\Menu\App\Queries\GetMenuTable;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Table\Table;

class Menus extends Items
{
    public string $type;

    public function mount(string $type, ?string $activeMenuId = null)
    {
        $this->type = $type;

        $this->mountItems(ChiefSites::locales(), $activeMenuId);
    }

    public function getMenuTable(string $menuId): Table
    {
        return app(GetMenuTable::class)->getTable($menuId);
    }

    public function addItem(): void
    {
        $this->dispatch('open-add-item')->to('chief-wire::add-menu');
    }

    public function editItem(string $itemId): void
    {
        $this->dispatch('open-edit-item', [
            'itemId' => $itemId,
        ])->to('chief-wire::edit-menu');
    }

    public function render()
    {
        return view('chief-menu::livewire.menus');
    }

    public function getItems(): Collection
    {
        return app(ComposeLivewireDto::class)->getMenus($this->type);
    }
}
