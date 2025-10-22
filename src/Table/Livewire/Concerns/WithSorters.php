<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Table\Sorters\Sorter;

trait WithSorters
{
    // Active sorters
    public array $sorters = [];

    public bool $showSorters = false;

    // Mount and set from session
    public function mountWithSorters(): void
    {
        $sessionSorters = session()->get($this->getSortersSessionKey());

        if (is_array($sessionSorters) && count($sessionSorters) > 0) {
            $this->sorters = $sessionSorters;

            return;
        }

        $this->applyDefaultSorters();
    }

    /**
     * @return Sorter[]
     */
    public function getSorters(): iterable
    {
        return $this->getTable()->getSorters();
    }

    public function getSortersForView(): iterable
    {
        return array_filter($this->getSorters(), function ($sorter) {
            return ! $sorter->hiddenFromView();
        });
    }

    public function getActiveSorters(): array
    {
        return array_map(function ($sorterKey) {
            return $this->findSorter($sorterKey);
        }, array_keys($this->sorters));
    }

    private function applyDefaultSorters()
    {
        $this->clearSorters();

        // The last defined default sorting as the default sorting to be used.
        // This way we can override the baked-in default tree sorting.
        foreach (array_reverse($this->getSorters()) as $sorter) {
            if ($sorter->actsAsDefault()) {
                $this->sorters[$sorter->getKey()] = $sorter->getValue();

                return;
            }
        }
    }

    private function applyQuerySorters(Builder $builder): void
    {
        foreach ($this->sorters as $sorterKey => $sorterValue) {
            if (($sorter = $this->findSorter($sorterKey)) && $sorter->hasQuery()) {
                $sorter->getQuery()($builder, $sorterValue);
            }
        }
    }

    private function applyCollectionSorters(Collection $rows): Collection
    {
        foreach ($this->sorters as $sorterKey => $sorterValue) {
            if (($sorter = $this->findSorter($sorterKey)) && $sorter->hasQuery()) {
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
        // We force only one sorting at a time for now.
        if (count($this->sorters) > 1) {
            $this->sorters = array_slice($this->sorters, -1, 1, true);
        }

        foreach ($this->sorters as $key => $sorterValue) {
            if ($this->isEmptySorterValue($sorterValue)) {
                unset($this->sorters[$key]);
            }
        }

        // Keep the personal sorting as a reference in session
        session()->put($this->getSortersSessionKey(), $this->sorters);

        $this->resetPage($this->getPaginationId());
    }

    private function findSorter(string $sorterKey): Sorter
    {
        foreach ($this->getSorters() as $sorter) {
            if ($sorter->getKey() == $sorterKey) {
                return $sorter;
            }
        }

        throw new \InvalidArgumentException('No sorter found by key '.$sorterKey);
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

        session()->forget($this->getSortersSessionKey());
    }

    public function addSorter()
    {
        // Empty target action for triggering a click event that
        // synchronizes the sorter values on button click
    }

    private function isEmptySorterValue($value): bool
    {
        return is_null($value) || empty($value) || $value === '';
    }

    private function getSortersSessionKey(): string
    {
        return 'table.sorters'.$this->tableReference->toUniqueString();
    }
}
