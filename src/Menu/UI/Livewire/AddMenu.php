<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Menu\App\Actions\CreateMenu;
use Thinktomorrow\Chief\Menu\App\Actions\MenuApplication;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;

class AddMenu extends Component
{
    use HasForm;
    use ShowsAsDialog;

    public string $type;

    public function mount(string $type)
    {
        $this->type = $type;
    }

    public function getListeners()
    {
        return [
            'open-add-menu' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->isOpen = true;

        $this->form['locales'] = ChiefLocales::locales();
    }

    public function close()
    {
        $this->reset(['form']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function save()
    {
        $this->validate([
            'form.locales' => 'required|array|min:1',
            'form.title' => 'required',
        ], [
            'form.locales.required' => 'Voeg minstens één taal toe. Dit bepaalt in welke talen je de fragmenten moet invullen.',
            'form.title.required' => 'Voorzie nog voor jezelf een titel. Kort en bondig.',
        ]);

        app(MenuApplication::class)->create(new CreateMenu(
            $this->type,
            $this->form['locales'],
            $this->form['title'],
        ));

        $this->dispatch($this->type.'-menus-updated');

        $this->close();
    }

    public function render()
    {
        return view('chief-menu::livewire.add-menu');
    }

    public function getAvailableLocales(): array
    {
        return PairOptions::toPairs(ChiefLocales::locales());
    }
}
