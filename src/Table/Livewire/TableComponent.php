<?php

namespace Thinktomorrow\Chief\Table\Livewire;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Thinktomorrow\Chief\Table\Columns\Column;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithActions;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithBulkActions;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithBulkSelection;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithFilters;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithHiddenFilters;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithMainFilters;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithNotifications;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithPagination as WithPaginationControl;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithRowActions;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithSorters;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithTreeResults;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Table\References\TableReference;

class TableComponent extends Component
{
    use WithPagination;
    use WithPaginationControl;
    use WithTreeResults;
    use WithFilters;
    use WithHiddenFilters;
    use WithMainFilters;
    use WithSorters;
    use WithActions;
    use WithRowActions;
    use WithBulkActions;
    use WithBulkSelection;
    use WithNotifications;

    public TableReference $tableReference;
    private ?Table $table = null;

    public ?int $resultPageCount = null;
    public ?int $resultTotal = null;

    public function mount(Table $table)
    {
        $this->table = $table;
        $this->tableReference = $table->getTableReference();
        $this->setDefaultFilters();
        $this->resetHiddenFilters();
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

    protected function getModelKeyName(): string
    {
        return $this->getTable()->getModelKeyName();
    }

    public function getHeaders(): array
    {
        return $this->getTable()->getHeaders();
    }

    public function render()
    {
        return view('chief-table::livewire.table', []);
    }

    public function getResults(bool $forceAsCollection = false): Collection|PaginatorContract
    {
        // Query source
        if ($this->getTable()->hasQuery()) {
            $builder = $this->getTable()->getQuery()();

            foreach ($this->getTable()->getAddedQueries() as $addedQuery) {
                $addedQuery($builder);
            }

            $this->applyQueryFilters($builder);
            $this->applyQuerySorters($builder);

            $results = $this->returnQueryResults($builder, $forceAsCollection);
        } elseif ($rows = $this->getTable()->getRows()) {
            $rows = $this->applyCollectionFilters($rows);
            $rows = $this->applyCollectionSorters($rows);

            $results = $this->returnCollectionResults($rows);
        } else {
            throw new \Exception('No query or rows defined for table.');
        }

        $this->resultPageCount = $results->count();
        $this->resultTotal = method_exists($results, 'total') ? $results->total() : $this->resultPageCount;

        return $results;
    }

    public function getResultsAsCollection(): Collection
    {
        return $this->getResults(true);
    }

    private function returnQueryResults(mixed $builder, bool $forceAsCollection = false): Collection|PaginatorContract
    {
        if ($forceAsCollection) {
            return $builder->get();
        }

        $this->areResultsAsTree = false;

        // Show tree structure when there are no sorters active
        if ($this->shouldReturnResultsAsTree()) {
            $this->areResultsAsTree = true;

            return $this->getResultsAsTree($builder, $this->getTable()->getResourceReference()->getResource(), $forceAsCollection);
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

        return (string) $model->{$this->getModelKeyName()};
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
