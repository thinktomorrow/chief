<?php

namespace Thinktomorrow\Chief\TableNew\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\TableNew\Sorters\Sorter;

trait WithSorters
{
    // Active sorters
    public array $sorters = [];

    public bool $showSorters = false;

    /**
     * @return Sorter[]
     */
    public function getSorters(): iterable
    {
        return $this->getTable()->getSorters();
    }

    public function getActiveSorterValue(string $sorterKey): string
    {
        if(($sorterValue = $this->findActiveSorterValue($sorterKey))) {
            return $sorterValue;
        }

        return '';
    }

    private function applyDefaultSorters()
    {
        $this->clearSorters();

        foreach($this->getSorters() as $sorter) {
            // Active either by present in url or set to active: active(), activeIfNone()
            if($defaultValue = $sorter->getValue()) {
                $this->sorters[$sorter->getKey()] = $defaultValue;
            }
        }
    }

    private function applyQuerySorters(Builder $builder): void
    {
        foreach ($this->sorters as $sorterKey => $sorterValue) {
            if(($sorter = $this->findSorter($sorterKey)) && $sorter->hasQuery()) {
                $sorter->getQuery()($builder, $sorterValue);
            }
        }
    }

    private function applyCollectionSorters(Collection $rows): Collection
    {
        foreach ($this->sorters as $sorterKey => $sorterValue) {
            if(($sorter = $this->findSorter($sorterKey)) && $sorter->hasQuery()) {
                $rows = $sorter->getQuery()($rows, $sorterValue);
            }
        }

        return $rows;
    }

    /**
     * Remove empty values out of the active sorters
     */
    public function updatedSorters()
    {
        foreach($this->sorters as $key => $sorterValue) {
            if($this->isEmptySorterValue($sorterValue)) {
                unset($this->sorters[$key]);
            }
        }
    }

    private function findSorter(string $sorterKey): Sorter
    {
        foreach($this->getSorters() as $sorter) {
            if($sorter->getKey() == $sorterKey) {
                return $sorter;
            }
        }

        throw new \InvalidArgumentException('No sorter found by key ' . $sorterKey);
    }

    private function findActiveSorterValue(string $sorterKey): mixed
    {
        return $this->sorters[$sorterKey] ?? null;
    }

    public function closeSorters()
    {
        $this->showSorters = false;

        $this->clearSorters();
        $this->applyDefaultSorters();
    }

    private function clearSorters()
    {
        $this->sorters = [];
    }

    public function addSorter()
    {
        // Empty target action for triggering a click event that
        // synchronizes the sorter values on button click
    }

    private function isEmptySorterValue($value): bool
    {
        return is_null($value) || empty($value) || '' === $value;
    }
}
