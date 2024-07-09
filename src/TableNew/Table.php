<?php

namespace Thinktomorrow\Chief\TableNew;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Table\Concerns\HasView;
use Thinktomorrow\Chief\TableNew\Columns\Column;
use Thinktomorrow\Chief\TableNew\Columns\Header;
use Thinktomorrow\Chief\TableNew\Columns\ColumnText;
use Thinktomorrow\Chief\TableNew\Concerns\HasQuery;
use Thinktomorrow\Chief\TableNew\Concerns\Table\HasRows;
use Thinktomorrow\Chief\TableNew\Concerns\Table\HasTableReference;
use Thinktomorrow\Chief\TableNew\Filters\SelectFilter;
use Thinktomorrow\Chief\TableNew\Filters\TextFilter;
use Thinktomorrow\Chief\TableNew\Livewire\TableComponent;

class Table extends Component
{
    use HasComponentRendering;
    use HasTableReference;

    /** Base Query for all table data */
    use HasQuery;
    use HasRows;

    protected string $view = 'chief-table-new::index';

    private array $headers = [];
    private array $columns = [];
    private array $filters = [];
    private array $sorters = [];
    private bool $paginate = false;
    private int $paginatePerPage = 10;

    // Default Livewire table component
    private string $livewireComponentClass = TableComponent::class;

    public static function make()
    {
        return new static();
    }

    public function usesLivewireTable(string $livewireComponentClass): static
    {
        $this->livewireComponentClass = $livewireComponentClass;

        return $this;
    }

    public function getLivewireComponentClass(): string
    {
        return $this->livewireComponentClass;
    }

    public function query(Closure|string $query): static
    {
        // A resource key can be passed to automatically resolve the query
        if(is_string($query) && $modelClassName = app(Registry::class)->resource($query)?->modelClassName()) {
            $query = function() use ($modelClassName) {
                return $modelClassName::query();
            };
        }

        $this->query = $query;

        return $this;
    }

    public function headers(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): array
    {
        return array_map(fn ($header) => (! $header instanceof Header) ? Header::make($header) : $header, $this->headers);
    }

    public function columns(array $columns): static
    {
        $this->columns = array_map(fn ($column) => ! $column instanceof Columns\Column ? Column::items([$column]) : $column, $columns);

        return $this;
    }

    public function getColumns($model): array
    {
        return $this->columns;
        //        return [
        //            $model->title,
        //            $this->createStateLabel($model->current_state),
        //            '<span class="text-sm text-grey-500">' . $model->updated_at->format('d/m/y H:m') . '</span>',
        //        ];
        //        return $this->columns;
    }

    public function filters(array $filters = []): static
    {
        // How to assign: primary, hidden,
        $this->filters = $filters;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function sorters(array $sorters = []): static
    {
        // How to assign: primary, hidden,
        $this->sorters = $sorters;

        return $this;
    }

    public function getSorters(): array
    {
        return $this->sorters;
    }

    public function paginate(int $paginatePerPage = 10): static
    {
        $this->paginate = true;
        $this->paginatePerPage = $paginatePerPage;

        return $this;
    }

    public function noPagination(): static
    {
        $this->paginate = false;

        return $this;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function setView(string $view): static
    {
        $this->view = $view;

        return $this;
    }
}
//
//Table::make()
////    ->columns('title', 'status', 'created_at')
//    ->headers([
//        'title' => 'Titel',
//        'status' => 'Status',
//        'created_at' => 'Aangemaakt op',
//    ])
////    ->columns(function ($model) {
////        return [
////            $model->title,
////            $this->createStateLabel($model->current_state),
////            '<span class="text-sm text-grey-500">' . $model->updated_at->format('d/m/y H:m') . '</span>',
////        ];
////    })
//    ->filters([
//    SelectFilter::make('status', function ($query) {
//        $query->where('status', 'published');
//    })->options([
//        'published' => 'Published',
//        'draft' => 'Draft',
//        'archived' => 'Archived',
//    ]),
////        ->displayAsPrimary()->displayInDropdown(),
//    TextFilter::make('title', function ($query, $value) {
//        $query->where('title', 'like', '%'.$value.'%');
//    }),
////    TextFilter::make('title')
////        ->query(function ($query, $value) {
////            $query->where('title', 'like', '%'.$value.'%');
////        })
////        ->label('Titel')
////        ->displayAsPrimary()
//])->sorters([
//    'title' => [
//        'type' => 'text',
//    ],
//    'created_at' => [
//        'type' => 'date',
//    ],
//])->paginate(20);
