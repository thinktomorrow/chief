<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Sites\ChiefSites;

abstract class EditItem extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithSafeDeletion;

    public TabItem $item;

    public array $locales;

    protected function mountEditItem(array $locales)
    {
        $this->locales = $locales;
    }

    public function getListeners()
    {
        return [
            'open-edit-item' => 'open',
            //            'allowed-sites-updated' => 'onAllowedSitesUpdated',
        ];
    }

    //    public function onAllowedSitesUpdated(array $allowedSites): void
    //    {
    //        $this->locales = $allowedSites;
    //    }

    abstract protected function getItemById(string $itemId): TabItem;

    public function open($values = [])
    {
        $this->item = $this->getItemById($values['itemId']);
        $this->locales = $this->item->getAllowedSites();

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
            'title' => $this->item->getTitle(),
            'locales' => $this->locales,
            'active_sites' => $this->item->getActiveSites(),
        ];
    }

    public function addActiveSite(string $locale): void
    {
        $this->form['active_sites'][] = $locale;
    }

    public function removeActiveSite(string $locale): void
    {
        $this->form['active_sites'] = array_values(array_diff($this->form['active_sites'], [$locale]));
    }

    public function close()
    {
        $this->reset(['form', 'locales', 'item', 'cannotBeDeleted', 'cannotBeDeletedBecauseOfLastLeft', 'cannotBeDeletedBecauseOfConnectedToSite']);
        $this->resetErrorBag();

        $this->isOpen = false;
    }

    public function deleteItem(): void
    {
        $this->handleDeleteItem();

        $this->dispatch('item-deleted', ['itemId' => $this->item->getId()]);

        $this->close();
    }

    abstract protected function handleDeleteItem(): void;

    abstract protected function handleUpdateItem(): void;

    public function save()
    {
        $this->validate([
            'form.locales' => ['required', 'array', 'min:1'],
            'form.title' => 'required',
        ], [
            'form.locales.required' => 'Duid minstens één site aan. Dit bepaalt in welke talen je de fragmenten kan invullen.',
            'form.title.required' => 'Voorzie nog voor jezelf een titel. Kort en bondig.',
        ]);

        // Active sites can only consist of the locales that are selected
        $this->form['active_sites'] = array_values(array_intersect($this->form['locales'], $this->form['active_sites']));

        $this->handleUpdateItem();

        $this->dispatch('item-updated', ...[
            'itemId' => $this->item->id,
        ]);

        $this->close();
    }

    abstract public function getItem(): TabItem;

    abstract public function getItemModels(): Collection;

    public function getAvailableLocales(): array
    {
        return ChiefSites::all()->filterByLocales($this->locales)->getLocales();
    }
}
