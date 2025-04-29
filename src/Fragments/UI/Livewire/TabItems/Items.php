<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Sites\ChiefSites;

abstract class Items extends Component
{
    use WithSafeDeletion;

    public array $locales;

    public ?string $activeItemId = null;

    public string $scopedLocale;

    protected function mountItems(array $locales, ?string $activeItemId = null)
    {
        $this->locales = $locales;

        if ($activeItemId) {
            $this->activeItemId = $activeItemId;
            $this->scopedLocale = ChiefSites::getLocaleScope();
        } else {
            $this->onScopedToLocale(ChiefSites::getLocaleScope());
        }
    }

    public function showTabs(): bool
    {
        return count($this->getItems()) > 1;
    }

    public function getListeners()
    {
        return [
            'item-updated' => 'onItemUpdated',
            'item-deleted' => 'onItemDeleted',
            'links-updated' => 'onSitesUpdated',
            'allowed-sites-updated' => 'onSitesUpdated',
            'scoped-to-locale' => 'onScopedToLocale',
        ];
    }

    abstract public function getItems(): Collection;

    abstract public function addItem(): void;

    abstract public function editItem(string $itemId): void;

    public function showItem(string $itemId): void
    {
        $this->activeItemId = $itemId;
    }

    public function onItemUpdated(string $itemId): void
    {
        // The items are automatically updated in the view
        $this->activeItemId = $itemId;
    }

    public function onSitesUpdated(): void
    {
        // The links are automatically updated in the view
        // because the getItems method is called again.
    }

    public function onScopedToLocale(string $locale): void
    {
        $this->scopedLocale = $locale;

        // Show the context for the scoped locale
        foreach ($this->getItems() as $item) {
            if (in_array($locale, $item->getActiveSites())) {
                $this->resetActiveItem($item->getId());

                return;
            }
        }
    }

    public function onItemDeleted(): void
    {
        // If the active item is deleted, reset the active item
        $this->resetActiveItem();
    }

    protected function resetActiveItem(?string $activeItemId = null): void
    {
        $this->activeItemId = (is_null($activeItemId))
            ? $this->getItems()->first()?->id
            : $activeItemId;
    }
}
