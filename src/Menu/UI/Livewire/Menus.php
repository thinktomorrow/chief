<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Menu\App\Queries\GetMenuTable;
use Thinktomorrow\Chief\Table\Table;

class Menus extends Component
{
    use WithMenus;

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
            'menus-updated' => 'onMenusUpdated',
        ];
    }

    public function showMenu(string $menuId): void
    {
        $this->activeMenuId = $menuId;
    }

    public function editMenus(): void
    {
        $this->dispatch('open-edit-menus')->to('chief-wire::edit-menus');
    }

    public function getMenuTable(string $menuId): Table
    {
        return app(GetMenuTable::class)->getTable($menuId);
    }

    public function onMenusUpdated(): void
    {
        // The links are automatically updated in the view
    }

    public function render()
    {
        return view('chief-menu::livewire.menus');
    }
}
