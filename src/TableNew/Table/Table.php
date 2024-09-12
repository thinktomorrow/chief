<?php

namespace Thinktomorrow\Chief\TableNew\Table;

use Closure;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\TableNew\Filters\Concerns\CanAddQuery;
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
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasRowViews;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasSorters;
use Thinktomorrow\Chief\TableNew\Table\Concerns\HasTreeLabelColumn;
use Thinktomorrow\Chief\TableNew\Table\References\HasResourceReference;
use Thinktomorrow\Chief\TableNew\Table\References\HasTableReference;
use Thinktomorrow\Chief\TableNew\Table\References\ResourceReference;

class Table extends Component
{
    use HasComponentRendering;
    use HasTableReference;
    use HasLivewireComponent;

    /** Tree support */
    use HasResourceReference;
    use HasTreeLabelColumn;

    /** Base Query for all table data */
    use HasQuery;
    use CanAddQuery;
    use HasRows;
    use HasRowViews;
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

    public function resource(string $resourceKey): static
    {
        $this->setResourceReference(new ResourceReference($resourceKey));

        if ($this->getResourceReference()->isTreeResource()) {
            $this->addDefaultTreeSorting();
        }

        $modelClassName = $this->getResourceReference()->getResource()->modelClassName();

        return $this->query(function () use ($modelClassName) {
            return $modelClassName::query();
        });

        //            // TODO: this should also be done when a custom query is passed like Page::online() instead of the resourcekey.
        //            if (in_array(Nestable::class, class_implements($modelClassName))) {
        //                $this->setResourceReference(new ResourceReference($resourceKey));
        //                $this->addDefaultTreeSorting();
        //            }
        //        }
        //
        //        return $this;
    }

    public function query(Closure $query): static
    {
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
