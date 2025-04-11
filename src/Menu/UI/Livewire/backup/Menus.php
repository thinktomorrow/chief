<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Menu\App\Queries\GetMenuTable;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Table\Table;

class Menus extends Component
{
    public string $type;

    public ?string $activeMenuId = null;

    public function mount(string $type, ?string $activeMenuId = null)
    {
        $this->type = $type;

        $this->activeMenuId = (is_null($activeMenuId))
            ? $this->getMenus()->first()?->id
            : $activeMenuId;
    }

    public function getListeners()
    {
        return [
            $this->type.'-menus-updated' => 'onMenusUpdated',
            $this->type.'-menu-deleted' => 'onMenuDeleted',
        ];
    }

    public function getMenus(): Collection
    {
        return Menu::where('type', $this->type)->get()->map(fn ($menu) => MenuDto::fromModel($menu));
    }

    public function showMenu(string $menuId): void
    {
        $this->activeMenuId = $menuId;
    }

    public function getMenuTable(string $menuId): Table
    {
        return app(GetMenuTable::class)->getTable($menuId);
    }

    public function addMenu(): void
    {
        $this->dispatch('open-add-menu')->to('chief-wire::add-menu');
    }

    public function editMenu(string $menuId): void
    {
        $this->dispatch('open-edit-menu', [
            'menuId' => $menuId,
        ])->to('chief-wire::edit-menu');
    }

    public function onMenusUpdated(): void
    {
        // The menus are automatically updated in the view
    }

    public function onMenuDeleted(): void
    {
        // The menus are automatically updated in the view

        // If the active menu is deleted, reset the active menu
        $this->resetActiveMenu();
    }

    private function resetActiveMenu(?string $activeMenuId = null): void
    {
        $this->activeMenuId = (is_null($activeMenuId))
            ? $this->getMenus()->first()?->id
            : $activeMenuId;
    }

    public function render()
    {
        return view('chief-menu::livewire.menus');
    }
}
