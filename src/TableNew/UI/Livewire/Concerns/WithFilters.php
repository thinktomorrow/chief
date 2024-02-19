<?php

namespace Thinktomorrow\Chief\TableNew\UI\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\TableNew\Filters\Filter;

trait WithFilters
{
    // Active filters
    public array $filters = [];

    private function applyDefaultFilters()
    {
        foreach($this->getFilters() as $filter) {
            // Active either by present in url or set to active: active(), activeIfNone()
            if($defaultValue = $filter->getValue()) {
                $this->filters[$filter->queryKey()] = $defaultValue;
            }
        }
    }

    /**
     * Remove empty values out of the active filters
     */
    public function updatedFilters()
    {
        foreach($this->filters as $key => $filterValue) {
            if($this->isEmptyFilterValue($filterValue) || (is_array($filterValue) && count($filterValue) == 1 && $this->isEmptyFilterValue(reset($filterValue)))) {
                unset($this->filters[$key]);
            }
        }
    }

    private function isEmptyFilterValue($value): bool
    {
        return is_null($value) || '' === $value;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): iterable
    {
        return [];
    }

    private function applyFilters(Builder $builder): void
    {
        foreach ($this->filters as $filterKey => $filterValue) {
            $this->findFilter($filterKey)
                ->query()($builder, $filterValue);
        }
    }

    private function findFilter(string $filterKey): Filter
    {
        foreach($this->getFilters() as $filter) {
            if($filter->queryKey() == $filterKey) {
                return $filter;
            }
        }

        throw new \InvalidArgumentException('No filter found by key ' . $filterKey);
    }
}
