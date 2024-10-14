<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

trait WithVariantFilters
{
    /** @var array Which filters are hidden in the drawer */
    public array $tertiaryFilterKeys = [];

    public function getPrimaryFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => $filter->isPrimary());
    }

    public function getSecondaryFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => $filter->isSecondary() && ! in_array($filter->getKey(), $this->tertiaryFilterKeys));
    }

    public function getTertiaryFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => in_array($filter->getKey(), $this->tertiaryFilterKeys));
    }

    public function getTertiaryFilterCount(): int
    {
        return count(array_filter(array_keys($this->getActiveFilters()), fn ($filterKey) => in_array($filterKey, $this->tertiaryFilterKeys)));
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
}
