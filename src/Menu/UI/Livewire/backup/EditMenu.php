<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Menu\App\Actions\DeleteMenu;
use Thinktomorrow\Chief\Menu\App\Actions\MenuApplication;
use Thinktomorrow\Chief\Menu\App\Actions\UpdateMenu;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Sites\ChiefSites;

class EditMenu extends Component
{
    use HasForm;
    use ShowsAsDialog;

    public string $type;

    public MenuDto $menu;

    public bool $cannotBeDeleted = false;

    public bool $cannotBeDeletedBecauseOfLastLeft = false;

    public bool $cannotBeDeletedBecauseOfConnectedToSite = false;

    public function mount(string $type)
    {
        $this->type = $type;
    }

    public function getListeners()
    {
        return [
            'open-edit-menu' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->menu = MenuDto::fromModel(Menu::find($values['menuId']));

        $this->setDeletionFlags();

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        $this->isOpen = true;
    }

    private function initialFormValues(): void
    {
        $this->form = [
            'title' => $this->menu->title,
            'locales' => $this->menu->locales,
        ];
    }

    public function close()
    {
        $this->reset(['form', 'menu', 'cannotBeDeleted', 'cannotBeDeletedBecauseOfLastLeft', 'cannotBeDeletedBecauseOfConnectedToSite']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function deleteMenu(): void
    {
        app(MenuApplication::class)->safeDelete(new DeleteMenu(
            $this->menu->id,
        ));

        $this->dispatch($this->type.'-menu-deleted', ['menuId' => $this->menu->id]);

        $this->close();
    }

    public function save()
    {
        $this->validate([
            'form.locales' => 'required|array|min:1',
            'form.title' => 'required',
        ], [
            'form.locales.required' => 'Voeg minstens één taal toe. Dit bepaalt in welke talen je de menu items moet invullen.',
            'form.title.required' => 'Voorzie nog voor jezelf een titel. Kort en bondig.',
        ]);

        app(MenuApplication::class)->update(new UpdateMenu(
            $this->menu->id,
            $this->form['locales'],
            $this->form['title'],
        ));

        $this->dispatch($this->type.'-menus-updated');

        $this->close();
    }

    public function render()
    {
        return view('chief-menu::livewire.edit-menu');
    }

    public function getAvailableLocales(): array
    {
        return PairOptions::toPairs(ChiefSites::locales());
    }

    private function setDeletionFlags(): void
    {
        if (count($this->menu->activeSites) > 0) {
            $this->cannotBeDeletedBecauseOfConnectedToSite = true;
            $this->cannotBeDeleted = true;
        }

        if (Menu::where('type', $this->type)->count() <= 1) {
            $this->cannotBeDeletedBecauseOfLastLeft = true;
            $this->cannotBeDeleted = true;
        }
    }
}
