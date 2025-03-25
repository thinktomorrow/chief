<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Menu\App\Actions\SaveMenus;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;

class EditMenus extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithMenus;

    public string $type;

    /** @var Collection<MenuDto> */
    public Collection $menus;

    public function mount(string $type)
    {
        $this->type = $type;
    }

    public function getListeners()
    {
        return [
            'open-edit-menus' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->menus = $this->getMenus();

        /**
         * Inject all field values in the Livewire form object
         * From then on we can use the form object to access the values
         */
        $this->initialFormValues();

        $this->isOpen = true;
    }

    public function close()
    {
        $this->reset(['form', 'menus']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function addMenu(): void
    {
        $this->menus->push(MenuDto::makeDefault($this->type, $this->menus->count() + 1));

        $this->initialFormValues();
    }

    public function deleteMenu(string $id): void
    {
        $this->form[$id] = null;
    }

    public function undoDeleteMenu(string $id): void
    {
        $this->initialFormValues();
    }

    public function save()
    {
        //        $this->validate([
        //            'form.*.slug' => 'required',
        //            'form.*.status' => 'required',
        //        ]);
        //        dd($this->form);
        app(SaveMenus::class)->handle($this->type, $this->form);

        $this->dispatch('menus-updated')
            ->to('chief-wire::menus');

        $this->close();
    }

    public function render()
    {
        return view('chief-menu::livewire.edit-menus');
    }

    public function queuedForDeletion(string $locale): bool
    {
        return ! isset($this->form[$locale]) || ! $this->form[$locale];
    }

    public function getAvailableLocales(): array
    {
        return PairOptions::toPairs(ChiefLocales::locales());
    }

    private function initialFormValues()
    {
        foreach ($this->menus as $menu) {

            if (isset($this->form[$menu->id])) {
                continue;
            }

            $this->form[$menu->id] = [
                'title' => $menu->title,
                'locales' => $menu->locales,
            ];
        }
    }
}
