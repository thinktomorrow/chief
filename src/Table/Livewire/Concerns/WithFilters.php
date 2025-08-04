<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Thinktomorrow\Chief\Table\Filters\Filter;
use Thinktomorrow\Chief\Table\Filters\SelectFilter;

trait WithFilters
{
    #[Url(history: true)]
    public array $filters = [];

    /** @var bool flag indicates if filter bar should be shown */
    public bool $showFilters = false;

    /** @return Filter[] */
    public function getFilters(): iterable
    {
        return $this->getTable()->getFilters();
    }

    public function getActiveFilters(): array
    {
        return array_filter($this->filters, fn ($filterValue) => ! $this->isEmptyFilterValue($filterValue));
    }

    /**
     * Display the filter value in the filter bar. For options we
     * want to display the label instead of the value.
     */
    public function getActiveFilterValue(string $filterKey): string
    {
        if (($filterValue = $this->findActiveFilterValue($filterKey))) {
            if (is_array($filterValue)) {
                return implode(', ', $this->getFilterValueFromOptions($filterKey, $filterValue));
            } else {
                return $filterValue;
            }
        }

        return '';
    }

    private function setDefaultFilters()
    {
        foreach ($this->getFilters() as $filter) {
            // Active either by present in url or set to active: active(), activeIfNone()
            if ($filter->hasValue() && ! isset($this->filters[$filter->getKey()])) {
                $this->filters[$filter->getKey()] = $filter->getValue();
            }
        }
    }

    private function applyQueryFilters(Builder $builder): void
    {
        foreach ($this->filters as $filterKey => $filterValue) {
            if (($filter = $this->findFilter($filterKey)) && $filter->hasQuery() && $filterValue) {
                $filter->getQuery()($builder, $filterValue);
            }
        }
    }

    private function applyCollectionFilters(Collection $rows): Collection
    {
        foreach ($this->filters as $filterKey => $filterValue) {
            if (($filter = $this->findFilter($filterKey)) && $filter->hasQuery() && $filterValue) {
                $rows = $filter->getQuery()($rows, $filterValue);
            }
        }

        return $rows;
    }

    /**
     * Remove empty values out of the active filters
     */
    public function updatedFilters()
    {
        foreach ($this->filters as $key => $filterValue) {
            if ($this->isEmptyFilterValue($filterValue)) {
                unset($this->filters[$key]);
            }
        }

        $this->resetPage($this->getPaginationId());

        // Allow Alpine to listen to this event
        $this->dispatch($this->getFiltersUpdatedEvent());
    }

    public function getFiltersUpdatedEvent(): string
    {
        return 'filters-updated-'.strtolower($this->getId());
    }

    public function getFilterValueFromOptions(string $filterKey, array $filterValue): array
    {
        $filter = $this->findFilter($filterKey);

        if ($filter instanceof SelectFilter) {
            $filterValue = array_map(function ($value) use ($filter) {
                return $filter->findLabelByValue($value) ?? $value;
            }, $filterValue);
        }

        return $filterValue;
    }

    private function findFilter(string $filterKey): Filter
    {
        foreach ($this->getFilters() as $filter) {
            if ($filter->getKey() == $filterKey) {
                return $filter;
            }
        }

        throw new \InvalidArgumentException('No filter found by key '.$filterKey);
    }

    private function findActiveFilterValue(string $filterKey): mixed
    {
        return $this->filters[$filterKey] ?? null;
    }

    public function closeFilters()
    {
        $this->showFilters = false;

        $this->resetFilters();
    }

    public function resetFilters()
    {
        $this->clearFilters();
        $this->setDefaultFilters();
        $this->updatedFilters();
    }

    public function clearFilters()
    {
        $this->filters = [];
    }

    public function addFilter()
    {
        // Empty target action for triggering a click event that
        // synchronizes the filter values on button click
    }

    private function isEmptyFilterValue($value): bool
    {
        if (is_array($value)) {
            return count($value) == 1 && $this->isEmptyFilterValue(reset($value));
        }

        return is_null($value) || empty($value) || $value === '';
    }
}
