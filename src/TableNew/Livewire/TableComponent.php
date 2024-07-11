<?php

namespace Thinktomorrow\Chief\TableNew\Livewire;

use Illuminate\Contracts\Pagination\CursorPaginator as CursorPaginatorContract;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Thinktomorrow\Chief\TableNew\Columns\Column;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithFilters;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithPagination as WithPaginationControl;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithSorters;
use Thinktomorrow\Chief\TableNew\Sorters\TreeSort;
use Thinktomorrow\Chief\TableNew\Table\Table;
use Thinktomorrow\Chief\TableNew\Table\TableReference;

class TableComponent extends Component
{
    use WithPagination;
    use WithPaginationControl;
    use WithFilters;
    use WithSorters;

    public TableReference $tableReference;
    private ?Table $table = null;

    public function mount(Table $table)
    {
        // TableId: PageClass where Table is configured and unique table key to retrieve the table
        // This way we can use closures :-)
        $this->table = $table;
        $this->tableReference = $table->getTableReference();
        //$this->applyDefaultFilters();
        $this->applyDefaultSorters();

        // active sorters - selected by user
        // default sorters - automatically active when no user selection
    }

    public function getTable(): Table
    {
        if(! $this->table) {
            $this->table = $this->tableReference->getTable();
        }

        return $this->table;
    }

    public function getHeaders(): array
    {
        return $this->getTable()->getHeaders();
    }

    public function render()
    {
        return view('chief-table-new::livewire.table', [
        ]);
    }

    public function getResults(): Collection|PaginatorContract
    {
        // Query source
        if($this->getTable()->hasQuery()) {
            $builder = $this->getTable()->getQuery()();

            $this->applyQueryFilters($builder);
            $this->applyQuerySorters($builder);

            return $this->returnQueryResults($builder);
        }

        // Collection source
        if($rows = $this->getTable()->getRows()) {
            $rows = $this->applyCollectionFilters($rows);
            $rows = $this->applyCollectionSorters($rows);

            return $this->returnCollectionResults($rows);
        }

        throw new \Exception('No query or rows defined for table.');
    }

    private function returnQueryResults(mixed $builder): Collection|PaginatorContract
    {
        // Show tree structure when there are no sorters active
        if($this->shouldReturnResultsAsTree()) {
            return $this->getResultsAsTree($builder, $this->getTable()->getTreeReference());
        }

        if(! $this->hasPagination()) {
            return $builder->get();
        }

        return $builder->paginate($this->getPaginationPerPage(), ['*'], $this->getPaginationId());
    }

    private function returnCollectionResults(Collection $rows): Collection|PaginatorContract
    {
        if(! $this->hasPagination()) {
            return $rows;
        }

        return (new LengthAwarePaginator($rows, count($rows), $this->getPaginationPerPage()))
            ->setPageName($this->getPaginationId());
    }

    /**
     * Get the results as a tree structure. This is used when the tree sorter is active.
     */
    private function getResultsAsTree(Builder $builder, string $treeResourceKey): Collection|PaginatorContract|CursorPaginatorContract
    {
        $builder->select('id');
        $result = $builder->get();

        $treeModels = app(TreeModels::class)
            ->create($treeResourceKey, $result->pluck('id')->toArray())
            ->all();

        if(! $this->hasPagination()) {
            return collect($treeModels);
        }

        // TODO: improve perf here because we know fetch ENTIRE tree for each query...
        $models = array_slice($treeModels, ($this->getCurrentPageIndex() - 1) * $this->getPaginationPerPage(), $this->getPaginationPerPage());

        // Prepend the ancestor models to the result if they are not present in the current page
        if(count($models) > 0) {
            $models = array_merge($models[0]->getAncestorNodes()->each(function ($node) {
                $node->getNodeEntry()->setAttribute('isAncestorRow', true);

                return $node;
            })->all(), $models);
        }

        return (new LengthAwarePaginator($models, count($result), 20, $this->getCurrentPageIndex()))
            ->setPageName($this->getPaginationId());
    }

    private function shouldReturnResultsAsTree(): bool
    {
        if(count($this->filters) > 0) {
            return false;
        }

        return count($this->sorters) == 1 && key($this->sorters) == TreeSort::TREE_SORTING && $this->getTable()->getTreeReference();
    }

    public function getColumns($model): array
    {
        return array_map(function (Column $column) use ($model) {
            return $column->model($model);
        }, $this->getTable()->getColumns($model));
    }

    /**
     * The unique key reference to the row. Used to reference
     * each row in the DOM for proper livewire diffing
     */
    public function getRowKey($model): string
    {
        if(is_array($model)) {
            return md5(print_r($model, true));
        }

        return (string) $model->getKey();
    }
}
