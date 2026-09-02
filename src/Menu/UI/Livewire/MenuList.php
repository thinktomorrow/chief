<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Attributes\Renderless;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\Items;
use Thinktomorrow\Chief\Menu\App\Queries\GetMenuTable;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Table\Table;

class MenuList extends Items
{
    public string $type;

    private ?Collection $menus = null;

    public function mount(string $type, ?string $activeMenuId = null)
    {
        $this->type = $type;

        $this->mountItems(ChiefSites::locales(), $activeMenuId);
    }

    public function getMenuTable(string $menuId): Table
    {
        return app(GetMenuTable::class)->getTable($menuId);
    }

    #[Renderless]
    public function addItem(): void
    {
        $this->dispatch('open-add-item')->to('chief-wire-menu::add-menu');
    }

    #[Renderless]
    public function editItem(string $itemId): void
    {
        $this->dispatch('open-edit-item', [
            'itemId' => $itemId,
        ])->to('chief-wire-menu::edit-menu');
    }

    public function render()
    {
        return view('chief-menu::livewire.menu-list');
    }

    public function getItems(): Collection
    {
        if ($this->menus) {
            return $this->menus;
        }

        return $this->menus = app(ComposeLivewireDto::class)->getMenus($this->type);
    }

    public function allowMultipleItems(): bool
    {
        return config('chief.allow_multiple_menus', false);
    }
}
