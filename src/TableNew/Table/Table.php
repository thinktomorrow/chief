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
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasColumns;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasFilters;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasHeaders;
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
    use HasFilters;
    use HasSorters;
    use HasHeaders;
    use HasColumns;
    use HasPagination;
    use HasActions;
    use HasBulkActions;
    use HasRowActions;

    protected string $view = 'chief-table-new::index';

    public static function make()
    {
        return new static();
    }

    public function query(Closure|string $query): static
    {
        // A resource key can be passed to automatically resolve the query
        if(is_string($query) && $modelClassName = app(Registry::class)->resource($query)?->modelClassName()) {
            $resourceKey = $query;
            $query = function () use ($modelClassName) {
                return $modelClassName::query();
            };

            // Is this a nestable model?
            // TODO: this should also be done when a custom query is passed like Page::online() instead of the resourcekey.
            if (in_array(Nestable::class, class_implements($modelClassName))) {
                $this->setTreeReference($resourceKey);
                $this->addDefaultTreeSorting();
            }
        }

        $this->query = $query;

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
