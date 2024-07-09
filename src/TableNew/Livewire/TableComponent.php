<?php

namespace Thinktomorrow\Chief\TableNew\Livewire;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableRepository;
use Thinktomorrow\Chief\TableNew\Columns\Column;
use Thinktomorrow\Chief\TableNew\Columns\ColumnText;
use Thinktomorrow\Chief\TableNew\Filters\Filter;
use Thinktomorrow\Chief\TableNew\Filters\FilterPresets;
use Thinktomorrow\Chief\TableNew\Filters\RadioFilter;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithFilters;
use Thinktomorrow\Chief\TableNew\Livewire\Concerns\WithSorters;
use Thinktomorrow\Chief\TableNew\Table;
use Thinktomorrow\Chief\TableNew\TableReference;

class TableComponent extends Component
{
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

    //    protected function getResource(): string
    //    {
    //        return \App\Models\Resources\Single::class;
    //    }

    //    private function convertToTree(): Paginator
    //    {
    //
    //    }

    public function getResults(): PaginatorContract
    {
        // Query source
        if($this->getTable()->hasQuery()) {
            $builder = $this->getTable()->getQuery()();

            $this->applyQueryFilters($builder);

            return $builder->paginate(20);
        }

        // Collection source
        $rows = $this->table->getRows();
        $rows = $this->applyCollectionFilters($rows);

        return new LengthAwarePaginator($rows, count($rows), 20);

        // GET ALL IDS
        //        $modelIds = DB::table('singles')->select(['id', 'parent_id'])->get()->map(fn($row) => (array) $row)->all();
        //        $collection = (new NodeCollectionFactory())->fromSource(new ArraySource($modelIds));
        //dd($collection);
        //
        //        // Paginate tree - flexible number
        //        dd($this->getTree());

        //        $builder = \App\Models\Resources\Single::withoutGlobalScopes()->online();


        //
        //        if ($model instanceof Nestable) {
        //            // TODO: this should be changed to the repository pattern like:
        //            // app($resource->indexRepository(), ['resourceKey' => $resourceKey])->applyFilters(request()->all())->getNestableResults()
        //            // indexModelIds can then be removed
        //            $filteredModelIds = $this->indexModelIds();
        //
        //            $filteredTree = $this
        //                ->getTree()
        //                ->shake(fn ($node) => in_array($node->getModel()->getKey(), $filteredModelIds));
        //
        //            View::share('tree', $filteredTree);
        //            View::share('originalModels', PairOptions::toPairs($this->getTree()->pluck('id', fn (NestedNode $nestedNode) => $nestedNode->getBreadCrumbLabel())));
        //        } else {
        //            // Used for duplicate action
        //            View::share('originalModels', ModelReferencePresenter::toSelectValues($model::all(), false, false));
        //            View::share('models', $this->indexModels());
        //        }
    }

    public function getColumns($model): array
    {
        return array_map(function (Column $column) use ($model) {
            return $column->model($model);
        }, $this->table->getColumns($model));
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

//    private function getTree(): iterable
//    {
//        // TODO: work with the indexRepository
//        // app($resource->indexRepository(), ['resourceKey' => $resourceKey])->applyFilters(request()->all())->getNestableResults()
//
//        return app(NestableRepository::class)->getTree($this->getResource()::resourceKey());
//    }
//
//    private function baseQuery(): Builder
//    {
//        return \App\Models\Pages\Page::query();
//    }
}
