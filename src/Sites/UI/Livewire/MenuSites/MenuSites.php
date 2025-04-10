<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\MenuSites;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\UI\Livewire\MenuDto;

class MenuSites extends Component
{
    use WithSites;

    public string $type;

    /** @var Collection<MenuDto> */
    public Collection $menus;

    public function mount(string $type)
    {
        $this->type = $type;
        $this->menus = Menu::where('type', $type)->get()->map(fn ($menu) => MenuDto::fromModel($menu));
    }

    public function getListeners()
    {
        return [
            'site-selection-updated' => 'onSitesUpdated',
            $this->type.'-menus-updated' => 'onMenusUpdated',
        ];
    }

    public function edit(): void
    {
        $this->dispatch('open-menu-edit-sites')->to('chief-wire::menu-edit-sites');
    }

    public function onSitesUpdated(): void
    {
        $this->refreshMenu();
    }

    public function onMenusUpdated(): void
    {
        $this->refreshMenu();
    }

    private function refreshMenu(): void
    {
        $this->menus = Menu::where('type', $this->type)->get()->map(fn ($menu) => MenuDto::fromModel($menu));
    }

    public function findActiveMenu(string $site): ?MenuDto
    {
        return $this->menus->first(fn ($menu) => $menu->hasActiveSite($site));
    }

    public function render()
    {
        return view('chief-sites::menu-sites.sites');
    }
}
