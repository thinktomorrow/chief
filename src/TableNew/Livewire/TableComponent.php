<?php

namespace Thinktomorrow\Chief\TableNew\Livewire;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Thinktomorrow\Chief\TableNew\Columns\Column;
use Thinktomorrow\Chief\TableNew\Columns\ColumnItem;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithActions;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithBulkActions;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithBulkSelection;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithFilters;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithPagination as WithPaginationControl;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithRowActions;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithSorters;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithTreeResults;
use Thinktomorrow\Chief\TableNew\Table\References\TableReference;
use Thinktomorrow\Chief\TableNew\Table\Table;

class TableComponent extends Component
{
    use WithPagination;
    use WithPaginationControl;
    use WithTreeResults;
    use WithFilters;
    use WithSorters;
    use WithActions;
    use WithRowActions;
    use WithBulkActions;
    use WithBulkSelection;

    public TableReference $tableReference;
    private ?Table $table = null;

    public function mount(Table $table)
    {
        $this->table = $table;
        $this->tableReference = $table->getTableReference();
        $this->setDefaultFilters();
        $this->applyDefaultSorters();

        // active sorters - selected by user
        // default sorters - automatically active when no user selection
    }

    public function getListeners()
    {
        return [
            'dialogSaved-' . $this->getId() => 'onActionDialogSaved',
            'requestRefresh' => '$refresh',
        ];
    }

    public function getTable(): Table
    {
        if (! $this->table) {
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
        return view('chief-table-new::livewire.table', []);
    }

    public function getResults(): Collection|PaginatorContract
    {
        // Query source
        if ($this->getTable()->hasQuery()) {
            $builder = $this->getTable()->getQuery()();

            foreach ($this->getTable()->getAddedQueries() as $addedQuery) {
                $addedQuery($builder);
            }

            $this->applyQueryFilters($builder);
            $this->applyQuerySorters($builder);

            return $this->returnQueryResults($builder);
        }

        // Collection source
        if ($rows = $this->getTable()->getRows()) {
            $rows = $this->applyCollectionFilters($rows);
            $rows = $this->applyCollectionSorters($rows);

            return $this->returnCollectionResults($rows);
        }

        throw new \Exception('No query or rows defined for table.');
    }

    private function returnQueryResults(mixed $builder): Collection|PaginatorContract
    {
        $this->areResultsAsTree = false;

        // Show tree structure when there are no sorters active
        if ($this->shouldReturnResultsAsTree()) {
            $this->areResultsAsTree = true;

            return $this->getResultsAsTree($builder, $this->getTable()->getResourceReference()->getResource());
        }

        if (! $this->hasPagination()) {
            return $builder->get();
        }

        return $builder->paginate($this->getPaginationPerPage(), ['*'], $this->getPaginationId());
    }

    public function areResultsAsTree(): bool
    {
        return $this->areResultsAsTree;
    }

    public function getAncestors(): array
    {
        return $this->ancestors;
    }

    private function returnCollectionResults(Collection $rows): Collection|PaginatorContract
    {
        if (! $this->hasPagination()) {
            return $rows;
        }

        return (new LengthAwarePaginator($rows, count($rows), $this->getPaginationPerPage()))
            ->setPageName($this->getPaginationId());
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
        if (is_array($model)) {
            return md5(print_r($model, true));
        }

        return (string) $model->getKey();
    }

    public function getRowView(): string
    {
        return $this->getTable()->getRowView();
    }

    /**
     * Used as label in the ancestor breadcrumb
     */
    public function getAncestorTreeLabel($model): ?ColumnItem
    {
        $columns = $this->getColumns($model);

        foreach ($columns as $column) {
            foreach ($column->getItems() as $columnItem) {
                if ($columnItem->getKey() == $this->getTable()->getTreeLabelColumn()) {
                    return $columnItem;
                }
            }
        }

        return null;
    }

    public function paginationView()
    {
        return 'chief::pagination.livewire-default';
    }
}
