<?php

namespace Thinktomorrow\Chief\TableNew\UI\Livewire;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableRepository;
use Thinktomorrow\Chief\TableNew\Filters\Filter;
use Thinktomorrow\Chief\TableNew\Filters\FilterPresets;
use Thinktomorrow\Chief\TableNew\Filters\Presets\RadioFilter;
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
            FilterPresets::search('title', [], ['title'])->placeholder('Zoek op titel'),
            // FilterPresets::state(),
            RadioFilter::make('online', function ($query, $value) {
                return $query->where('current_state', '=', $value);
            })->label('Status')->options([
                '' => 'Alle',
                PageState::published->getValueAsString() => 'Online',
                PageState::draft->getValueAsString() => 'Offline',
            ])->default(''),
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

        return $builder->paginate(20);

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
            $model->title,
            $this->createStateLabel($model->current_state),
            '<span class="text-sm text-grey-500">' . $model->updated_at->format('d/m/y H:m') . '</span>',
        ];
    }

    public function createStateLabel($state): string
    {
        return match ($state) {
            PageState::published->getValueAsString() => '<span class="bui-label bui-label-xs bui-label-green">Online</span>',
            PageState::draft->getValueAsString() => '<span class="bui-label bui-label-xs bui-label-red">Offline</span>',
            PageState::archived->getValueAsString() => '<span class="bui-label bui-label-xs bui-label-red">Gearchiveerd</span>',
            default => '<span class="bui-label bui-label-xs bui-label-grey">Draft</span>',
        };
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
