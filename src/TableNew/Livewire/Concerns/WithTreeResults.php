<?php

namespace Thinktomorrow\Chief\TableNew\Livewire\Concerns;

use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\TableNew\Livewire\TreeModels;
use Thinktomorrow\Chief\TableNew\Sorters\TreeSort;

trait WithTreeResults
{
    // After a tree result, we have the ancestors to show the tree structure and this boolean as flag for tree structure;
    private bool $areResultsAsTree = false;

    // The ancestors are used to show the tree structure of the first row in the table
    private array $ancestors = [];

    /**
     * Get the results as a tree structure. This is used when the tree sorter is active.
     */
    private function getResultsAsTree(Builder $builder, TreeResource $treeResource): Collection|PaginatorContract|CursorPaginatorContract
    {
        // TODO: 'id' is assumed here. This should be configurable...
        $result = $builder
            ->select('id')
            ->toBase()
            ->get();

        [$ancestors, $treeModels] = app(TreeModels::class)->compose(
            $treeResource,
            $result->pluck('id')->toArray(),
            $this->hasPagination() ? ($this->getCurrentPageIndex() - 1) * $this->getPaginationPerPage() : 0,
            $this->hasPagination() ? $this->getPaginationPerPage() : count($result)
        );

        $this->ancestors = $ancestors->all();

        if (! $this->hasPagination()) {
            return collect($treeModels);
        }

        // TODO: improve perf here because we know fetch ENTIRE tree for each query...

        return (new LengthAwarePaginator($treeModels, count($result), 20, $this->getCurrentPageIndex()))
            ->setPageName($this->getPaginationId());
    }

    private function shouldReturnResultsAsTree(): bool
    {
        if (! $this->getTable()->getResourceReference()->isTreeResource()) {
            return false;
        }

        // If filtering is active, we never show the tree structure
        if (count($this->getActiveFilters()) > 0) {
            return false;
        }

        // Only if tree sorting is active, we show the tree structure
        return count($this->sorters) == 1 && key($this->sorters) == TreeSort::TREE_SORTING;
    }
}
