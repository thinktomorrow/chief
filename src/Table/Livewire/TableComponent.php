<?php

namespace Thinktomorrow\Chief\Table\Livewire;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Thinktomorrow\Chief\Table\Filters\Presets\SiteFilter;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithActions;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithBulkActions;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithBulkSelection;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithColumns;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithColumnSelection;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithFilters;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithNotifications;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithPagination as WithPaginationControl;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithReordering;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithRowActions;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithSorters;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithTreeResults;
use Thinktomorrow\Chief\Table\Livewire\Concerns\WithVariantFilters;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Table\References\TableReference;

class TableComponent extends Component
{
    use WithActions;
    use WithBulkActions;
    use WithBulkSelection;
    use WithColumns;
    use WithColumnSelection;
    use WithFilters;
    use WithNotifications;
    use WithPagination;
    use WithPaginationControl;
    use WithReordering;
    use WithRowActions;
    use WithSorters;
    use WithTreeResults;
    use WithVariantFilters;

    public TableReference $tableReference;

    private ?Table $table = null;

    public ?int $resultPageCount = null;

    public ?int $resultTotal = null;

    public string $variant = 'card';

    public array $customListeners = [];

    public string $locale;

    public function mount(Table $table)
    {
        /** Can be altered by the site filter / default filter */
        $this->locale = app()->getLocale();

        $this->table = $table;
        $this->tableReference = $table->getTableReference();
        $this->setDefaultFilters();
        $this->resetTertiaryFilters();
        $this->applyDefaultSorters();

        if ($table->isReorderingAllowed() && $table->isStartingWithReordering()) {
            $this->startReordering();
        }

        // Any custom listeners
        $this->customListeners = $table->getListeners();
        // active sorters - selected by user
        // default sorters - automatically active when no user selection
    }

    public function getListeners()
    {
        return array_merge([
            'dialogSaved-'.$this->getId() => 'onActionDialogSaved',
            'requestRefresh-'.$this->getId() => '$refresh',
            'requestRefresh' => '$refresh',
            'scoped-to-locale' => 'onScopedToLocale',
        ], $this->customListeners);
    }

    public function onScopedToLocale($locale)
    {
        // Filter result by site...
        foreach ($this->getFilters() as $filter) {
            if ($filter instanceof SiteFilter) {
                $this->filters[$filter->getKey()] = $locale;

                // Allow Alpine to listen to this event
                $this->dispatch($this->getFiltersUpdatedEvent());
            }
        }
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

    public function render()
    {
        if ($this->isReordering) {
            return view('chief-table::reorder.list', ['variant' => $this->variant]);
        }

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

            $treeResource = $this->getTable()->getTreeResource() ?: $this->getTable()->getResourceReference()->getResource();

            return $this->getResultsAsTree($builder, $treeResource, $forceAsCollection);
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

    public function isTableHeaderShown(): bool
    {
        if ($this->isReordering) {
            return false;
        }

        return count($this->getFilters()) > 0 || count($this->getSorters()) > 1 || $this->allowColumnSelection();
    }

    /**
     * TODO(ben): Implement this
     * Should return false when there aren't any records created yet
     */
    public function hasRecords(): bool
    {
        return true;
    }

    public function paginationView()
    {
        return 'chief::pagination.livewire-default';
    }
}
