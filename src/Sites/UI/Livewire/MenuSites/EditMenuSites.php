<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire\MenuSites;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuType;
use Thinktomorrow\Chief\Menu\UI\Livewire\MenuDto;
use Thinktomorrow\Chief\Sites\Actions\SaveMenuSites;

class EditMenuSites extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithSites;

    public string $type;

    public Collection $sites;

    /** @var Collection<MenuDto> */
    public Collection $menus;

    public function mount(string $type)
    {
        $this->type = $type;
    }

    public function getListeners()
    {
        return [
            'open-menu-edit-sites' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->sites = $this->getSites();
        $this->menus = Menu::where('type', $this->type)->get()->map(fn ($menu) => MenuDto::fromModel($menu));

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['form', 'sites', 'menus']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function save()
    {
        app(SaveMenuSites::class)->handle(MenuType::find($this->type), $this->form);

        $this->dispatch('sites-updated')
            ->to('chief-wire::menu-sites');

        $this->close();
    }

    public function render()
    {
        return view('chief-sites::menu-sites.edit-sites');
    }

    private function initialFormValues()
    {
        foreach ($this->sites as $site) {

            // Get menu for this site, if any
            $menu = $this->menus->first(fn ($menu) => in_array($site->locale, $menu->activeSites));

            $this->form[$site->locale] = [
                'menu' => $menu?->id,
            ];
        }
    }
}
