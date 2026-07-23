<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Thinktomorrow\Chief\Table\Filters\Filter;
use Thinktomorrow\Chief\Table\Filters\Presets\SiteFilter;
use Thinktomorrow\Chief\Table\Filters\SelectFilter;

trait WithFilters
{
    #[Url(history: true)]
    public array $filters = [];

    /** @var bool flag indicates if filter bar should be shown */
    public bool $showFilters = false;

    public array $tableFilterScopeState = [];

    public function mountWithFilters(): void
    {
        // Alleen restoren als er nog geen filters via URL zijn ingesteld
        if ($this->isUsingDefaultFilters() && session()->has($this->getFilterSessionKey())) {
            $this->filters = session($this->getFilterSessionKey());
        }

        $this->tableFilterScopeState = $this->scopeState($this->filters);
    }

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
            return implode(', ', $this->getFilterValueFromOptions($filterKey, (array) $filterValue));
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

        $this->syncLocaleWithSiteFilter();
    }

    private function isUsingDefaultFilters(): bool
    {
        foreach ($this->getFilters() as $filter) {
            $activeFilterValue = $this->findActiveFilterValue($filter->getKey());

            if ($activeFilterValue != $filter->getValue()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Remove empty values out of the active filters
     */
    public function removeEmptyFilters(): void
    {
        foreach ($this->filters as $key => $filterValue) {
            if ($this->isEmptyFilterValue($filterValue)) {
                unset($this->filters[$key]);
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

    public function updatedFilters()
    {
        $this->removeEmptyFilters();

        $this->syncLocaleWithSiteFilter();

        $this->restoreFiltersForChangedScope();

        session()->put($this->getFilterSessionKey(), $this->filters);

        $this->tableFilterScopeState = $this->scopeState($this->filters);

        $this->resetPage($this->getPaginationId());

        // Allow Alpine to listen to this event
        $this->dispatch($this->getFiltersUpdatedEvent());
    }

    protected function syncLocaleWithSiteFilter(): void
    {
        foreach ($this->getFilters() as $filter) {
            if ($filter instanceof SiteFilter && isset($this->filters[$filter->getKey()])) {
                $this->locale = $this->filters[$filter->getKey()];
            }
        }
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

    protected function findFilter(string $filterKey): Filter
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
        $previousFilterSessionKey = $this->getFilterSessionKey();

        $this->clearFilters();
        $this->setDefaultFilters();

        $this->removeEmptyFilters();
        $this->syncLocaleWithSiteFilter();

        session()->forget($previousFilterSessionKey);
        session()->forget($this->getFilterSessionKey());

        $this->tableFilterScopeState = $this->scopeState($this->filters);

        $this->resetPage($this->getPaginationId());

        // Allow Alpine to listen to this event
        $this->dispatch($this->getFiltersUpdatedEvent());
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

        return is_null($value) || $value === '' || (is_array($value) && empty($value));
    }

    protected function getFilterSessionKey(): string
    {
        return 'table.filters.'.$this->tableReference->toUniqueString().$this->getTableScopeSessionKeySuffix();
    }

    /**
     * Build the optional session-key suffix for the active scoped filter values.
     */
    protected function getTableScopeSessionKeySuffix(?array $filters = null): string
    {
        if (! $this->hasScopeFilters()) {
            return '';
        }

        return '.'.md5(json_encode($this->normalizedScopeState($filters ?? $this->filters)));
    }

    private function hasScopeFilters(): bool
    {
        return count($this->getScopeFilters()) > 0;
    }

    /** @return Filter[] */
    private function getScopeFilters(): array
    {
        return array_values(array_filter($this->getFilters(), fn (Filter $filter): bool => $filter->scopesTableState()));
    }

    /**
     * Extract only the active scope-defining filter values from the given filter state.
     */
    private function scopeState(array $filters): array
    {
        return collect($this->getScopeFilters())
            ->mapWithKeys(fn (Filter $filter): array => [$filter->getKey() => $filters[$filter->getKey()] ?? $filter->getValue()])
            ->all();
    }

    /**
     * Normalize scope values before hashing so filter order and multi-select order do not matter.
     */
    private function normalizedScopeState(array $filters): array
    {
        $scopeState = $this->scopeState($filters);

        ksort($scopeState);

        return array_map(function ($value) {
            if (is_array($value)) {
                sort($value);
            }

            return $value;
        }, $scopeState);
    }

    /**
     * Swap scoped filters and sorters when a scope-defining filter changes.
     */
    private function restoreFiltersForChangedScope(): void
    {
        if (! $this->hasScopeFilters()) {
            return;
        }

        $currentScopeState = $this->scopeState($this->filters);

        if ($this->tableFilterScopeState === [] || $this->tableFilterScopeState === $currentScopeState) {
            return;
        }

        $this->filters = array_merge(
            session($this->getFilterSessionKey(), []),
            $this->filtersWithoutScopedTableState($this->filters),
            $currentScopeState,
        );

        if (property_exists($this, 'sorters')) {
            $this->sorters = array_merge(
                session($this->getSortersSessionKey(), []),
                $this->sortersWithoutScopedTableState($this->sorters),
            );
        }
    }

    /**
     * Keep non-scoped filter values when switching between scoped session keys.
     */
    private function filtersWithoutScopedTableState(array $filters): array
    {
        foreach ($this->scopedFilterKeys() as $filterKey) {
            unset($filters[$filterKey]);
        }

        return $filters;
    }

    /**
     * Keep non-scoped sorter values when switching between scoped session keys.
     */
    protected function sortersWithoutScopedTableState(array $sorters): array
    {
        foreach ($this->scopedSorterKeys() as $sorterKey) {
            unset($sorters[$sorterKey]);
        }

        return $sorters;
    }

    /**
     * List filter keys whose state should be isolated by the active scope filters.
     */
    private function scopedFilterKeys(): array
    {
        return collect($this->getFilters())
            ->reject(fn (Filter $filter): bool => $filter->scopesTableState())
            ->filter(fn (Filter $filter): bool => $this->isScopedTableStateKey($filter->getKey()))
            ->map(fn (Filter $filter): string => $filter->getKey())
            ->values()
            ->all();
    }

    /**
     * List sorter keys whose state should be isolated by the active scope filters.
     */
    private function scopedSorterKeys(): array
    {
        if (! method_exists($this, 'getSorters')) {
            return [];
        }

        return collect($this->getSorters())
            ->filter(fn ($sorter): bool => $this->isScopedTableStateKey($sorter->getKey()))
            ->map(fn ($sorter): string => $sorter->getKey())
            ->values()
            ->all();
    }

    private function isScopedTableStateKey(string $key): bool
    {
        return collect($this->getScopeFilters())
            ->contains(fn (Filter $scopeFilter): bool => $scopeFilter->scopesTableStateKey($key));
    }
}
