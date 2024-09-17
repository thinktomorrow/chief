<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Table\Filters\Filter;

trait WithHiddenFilters
{
    /** @var array Which filters are hidden in the drawer */
    public array $hiddenFilterKeys = [];

    public function getVisibleFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => ! in_array($filter->getKey(), $this->hiddenFilterKeys));
    }

    public function getHiddenFilters(): array
    {
        return array_filter($this->getFilters(), fn ($filter) => in_array($filter->getKey(), $this->hiddenFilterKeys));
    }

    public function areAnyHiddenFiltersActive(): bool
    {
        return count(array_filter(array_keys($this->getActiveFilters()), fn ($filterKey) => in_array($filterKey, $this->hiddenFilterKeys))) > 0;
    }

    public function hideFilter($filterKey)
    {
        $this->hiddenFilterKeys[] = $filterKey;
    }

    /**
     * Reset the hidden filters to the default state
     */
    public function resetHiddenFilters()
    {
        $this->hiddenFilterKeys = [];

        foreach ($this->getFilters() as $filter) {
            if ($filter->isHidden()) {
                $this->hiddenFilterKeys[] = $filter->getKey();
            }
        }
    }
}
