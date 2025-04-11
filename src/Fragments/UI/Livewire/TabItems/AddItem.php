<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Sites\ChiefSites;

abstract class AddItem extends Component
{
    use HasForm;
    use ShowsAsDialog;

    public array $locales;

    public function mountAddItem(array $locales)
    {
        $this->locales = $locales;
    }

    public function getListeners()
    {
        return [
            'open-add-item' => 'open',
        ];
    }

    public function open($values = [])
    {
        $this->isOpen = true;

        $this->form['locales'] = ChiefSites::locales();
        $this->form['active_sites'] = [];
    }

    abstract public function getItem(): TabItem;

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

        $itemId = $this->createOnSave();

        $this->dispatch('item-updated', ...[
            'itemId' => $itemId,
        ]);

        $this->close();
    }

    abstract protected function createOnSave(): string;

    public function getAvailableLocales(): array
    {
        return ChiefSites::all()->filterByLocales($this->locales)->toCollection()->pluck('shortName', 'locale')->all();
    }
}
