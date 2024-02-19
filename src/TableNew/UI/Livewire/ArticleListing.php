<?php

namespace Thinktomorrow\Chief\TableNew\UI\Livewire;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableRepository;
use Thinktomorrow\Chief\TableNew\Filters\Filter;
use Thinktomorrow\Chief\TableNew\Filters\FilterPresets;
use Thinktomorrow\Chief\TableNew\UI\Livewire\Concerns\WithFilters;

class ArticleListing extends Listing
{
    use WithFilters;

    public function mount()
    {
        $this->applyDefaultFilters();
    }

    public function download()
    {
        dd('sisi');
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): iterable
    {
        // TODO: listen to request input for default as well
        return [
            FilterPresets::search('title', [], ['title'])->placeholder('zoek op titel'),
            FilterPresets::state(),
        ];
    }

    protected function getResource(): string
    {
        return \App\Models\Resources\Single::class;
    }

    //    private function convertToTree(): Paginator
    //    {
    //
    //    }

    private function getTree(): iterable
    {
        // TODO: work with the indexRepository
        // app($resource->indexRepository(), ['resourceKey' => $resourceKey])->applyFilters(request()->all())->getNestableResults()

        return app(NestableRepository::class)->getTree($this->getResource()::resourceKey());
    }

    private function baseQuery(): Builder
    {
        return \App\Models\Resources\Single::query();
    }

    public function getModels(): Paginator
    {
        // TREE traversal is slow... entire tree is necessary ... IS IT?

        //        dd($this->filters);
        $builder = $this->baseQuery();

        $this->applyFilters($builder);

        return $builder->simplePaginate(20);

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

    public function getRow($model): iterable
    {
        return [
            $model->id,
            $model->title,
//            TableColumn::image($model->asset('image')->url('thumb')),
        ];
    }

    /**
     * The unique key reference to the row. Used to reference
     * each row in the DOM for proper livewire diffing
     */
    public function getRowKey($model): string
    {
        return (string)$model->getKey();
    }
}
