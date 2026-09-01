<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

trait WithVariantFilters
{
    /** @var array Which filters are hidden in the drawer */
    public array $tertiaryFilterKeys = [];

    /** @var string[] Filters that had options when the table was initialized */
    public array $initiallyVisibleFilterKeys = [];

    public function initializeVisibleFilters(): void
    {
        $this->initiallyVisibleFilterKeys = collect($this->getFilters())
            ->filter(fn ($filter): bool => $filter->shouldInitiallyRender())
            ->map(fn ($filter): string => $filter->getKey())
            ->values()
            ->all();
    }

    public function getPrimaryFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => $filter->isPrimary() && $this->isInitiallyVisible($filter->getKey()));
    }

    public function getSecondaryFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => $filter->isSecondary()
            && ! in_array($filter->getKey(), $this->tertiaryFilterKeys)
            && $this->isInitiallyVisible($filter->getKey()));
    }

    public function getTertiaryFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => in_array($filter->getKey(), $this->tertiaryFilterKeys)
            && $this->isInitiallyVisible($filter->getKey()));
    }

    public function getTertiaryFilterCount(): int
    {
        return count(array_filter(array_keys($this->getActiveFilters()), fn ($filterKey) => in_array($filterKey, $this->tertiaryFilterKeys)
            && $this->isInitiallyVisible($filterKey)));
    }

    public function setFilterAsTertiary($filterKey)
    {
        $this->tertiaryFilterKeys[] = $filterKey;
    }

    /**
     * Reset the hidden filters to the default state
     */
    public function resetTertiaryFilters()
    {
        $this->tertiaryFilterKeys = [];

        foreach ($this->getFilters() as $filter) {
            if ($filter->isTertiary()) {
                $this->tertiaryFilterKeys[] = $filter->getKey();
            }
        }
    }

    private function isInitiallyVisible(string $filterKey): bool
    {
        return in_array($filterKey, $this->initiallyVisibleFilterKeys);
    }
}
