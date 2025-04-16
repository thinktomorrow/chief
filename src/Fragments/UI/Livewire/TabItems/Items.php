<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems;

use Illuminate\Support\Collection;
use Livewire\Component;

abstract class Items extends Component
{
    use WithSafeDeletion;

    public array $locales;

    public ?string $scopedLocale = null;

    public ?string $activeItemId = null;

    protected function mountItems(array $locales, ?string $activeItemId = null)
    {
        $this->locales = $locales;

        $this->resetActiveItem($activeItemId);
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
            'site-links-updated' => 'onSitesUpdated',
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
                $this->activeItemId = $item->getId();

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
