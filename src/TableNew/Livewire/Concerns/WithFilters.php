<?php

namespace Thinktomorrow\Chief\TableNew\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\TableNew\Filters\Filter;

trait WithFilters
{
    // Active filters
    public array $filters = [];

    public bool $showFilters = false;

    /**
     * @return Filter[]
     */
    public function getFilters(): iterable
    {
        return $this->getTable()->getFilters();
    }

    public function getActiveFilterValue(string $filterKey): string
    {
        if(($filterValue = $this->findActiveFilterValue($filterKey))) {
            if(is_array($filterValue)) {
                return implode(', ', $this->getFilterValueFromOptions($filterKey, $filterValue));
            } else {
                return $filterValue;
            }
        }

        return '';
    }

    private function applyDefaultFilters()
    {
        $this->clearFilters();

        foreach($this->getFilters() as $filter) {
            // Active either by present in url or set to active: active(), activeIfNone()
            if($defaultValue = $filter->getValue()) {
                $this->filters[$filter->getKey()] = $defaultValue;
            }
        }
    }

    private function applyQueryFilters(Builder $builder): void
    {
        foreach ($this->filters as $filterKey => $filterValue) {
            if(($filter = $this->findFilter($filterKey)) && $filter->hasQuery()) {
                $filter->getQuery()($builder, $filterValue);
            }
        }
    }

    private function applyCollectionFilters(Collection $rows): Collection
    {
        foreach ($this->filters as $filterKey => $filterValue) {
            if(($filter = $this->findFilter($filterKey)) && $filter->hasQuery()) {
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
        foreach($this->filters as $key => $filterValue) {
            if($this->isEmptyFilterValue($filterValue) || (is_array($filterValue) && count($filterValue) == 1 && $this->isEmptyFilterValue(reset($filterValue)))) {
                unset($this->filters[$key]);
            }
        }
    }

    /**
     * @param string $filterKey
     * @param array $filterValue
     * @return array
     */
    public function getFilterValueFromOptions(string $filterKey, array $filterValue): array
    {
        $filter = $this->findFilter($filterKey);

        if (method_exists($filter, 'getOptions')) {
            $options = $filter->getOptions();
            $filterValue = array_map(function ($value) use ($options) {
                foreach ($options as $option) {
                    if ($option['value'] == $value) {
                        return $option['label'];
                    }
                }

                return $value;
            }, $filterValue);
        }

        return $filterValue;
    }

    private function findFilter(string $filterKey): Filter
    {
        foreach($this->getFilters() as $filter) {
            if($filter->getKey() == $filterKey) {
                return $filter;
            }
        }

        throw new \InvalidArgumentException('No filter found by key ' . $filterKey);
    }

    private function findActiveFilterValue(string $filterKey): mixed
    {
        return $this->filters[$filterKey] ?? null;
    }

    public function closeFilters()
    {
        $this->showFilters = false;

        $this->clearFilters();
        $this->applyDefaultFilters();
    }

    private function clearFilters()
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
        return is_null($value) || empty($value) || '' === $value;
    }
}
