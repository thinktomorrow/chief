<?php

namespace Thinktomorrow\Chief\TableNew\Table;

use Closure;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\TableNew\Columns\Column;
use Thinktomorrow\Chief\TableNew\Columns\Header;
use Thinktomorrow\Chief\TableNew\Filters\Concerns\HasQuery;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasActions;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasBulkActions;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasLivewireComponent;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasPagination;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasRowActions;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasRows;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasSorters;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasTableReference;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasTreeReference;

class Table extends Component
{
    use HasComponentRendering;
    use HasTableReference;
    use HasLivewireComponent;
    use HasTreeReference;

    /** Base Query for all table data */
    use HasQuery;
    use HasRows;
    use HasSorters;
    use HasPagination;
    use HasActions;
    use HasBulkActions;
    use HasRowActions;

    protected string $view = 'chief-table-new::index';

    private array $headers = [];
    private array $columns = [];
    private array $filters = [];
    private array $defaultSorters = [];

    public static function make()
    {
        return new static();
    }

    public function query(Closure|string $query): static
    {
        // A resource key can be passed to automatically resolve the query
        if(is_string($query) && $modelClassName = app(Registry::class)->resource($query)?->modelClassName()) {
            $query = function () use ($modelClassName) {
                return $modelClassName::query();
            };

            // Is this a nestable model?
            // TODO: this should also be done when a custom query is passed like Page::online() instead of the resourcekey.
            if (in_array(Nestable::class, class_implements($modelClassName))) {
                $resourceKey = app(Registry::class)->findResourceByModel($modelClassName)::resourceKey();
                $this->setTreeReference($resourceKey);

                $this->addDefaultTreeSorting();
            }
        }

        $this->query = $query;

        return $this;
    }

    public function headers(array $headers): static
    {
        $this->headers = array_map(fn ($header) => (! $header instanceof Header) ? Header::make($header) : $header, $headers);

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function columns(array $columns): static
    {
        $this->columns = array_map(fn ($column) => ! $column instanceof Column ? Column::items([$column]) : $column, $columns);

        // If no headers are explicitly set, we will use the column labels as headers
        if (empty($this->headers)) {
            $this->headers = collect($this->columns)
                ->reject(fn ($column) => empty($column->getItems()))
                ->map(fn ($column) => Header::make($column->getItems()[0]->getLabel()))
                ->all();
        }

        return $this;
    }

    public function getColumns($model): array
    {
        return $this->columns;
    }

    public function filters(array $filters = []): static
    {
        // How to assign: primary, hidden,
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
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
