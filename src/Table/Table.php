<?php

namespace Thinktomorrow\Chief\Table;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Forms\Concerns\HasPosition;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Shared\Concerns\Sortable\Sortable;
use Thinktomorrow\Chief\Table\Filters\Concerns\CanAddQuery;
use Thinktomorrow\Chief\Table\Filters\Concerns\HasQuery;
use Thinktomorrow\Chief\Table\Table\Concerns\HasActions;
use Thinktomorrow\Chief\Table\Table\Concerns\HasBulkActions;
use Thinktomorrow\Chief\Table\Table\Concerns\HasColumns;
use Thinktomorrow\Chief\Table\Table\Concerns\HasFilters;
use Thinktomorrow\Chief\Table\Table\Concerns\HasHeaders;
use Thinktomorrow\Chief\Table\Table\Concerns\HasLivewireComponent;
use Thinktomorrow\Chief\Table\Table\Concerns\HasLivewireListeners;
use Thinktomorrow\Chief\Table\Table\Concerns\HasModelKeyName;
use Thinktomorrow\Chief\Table\Table\Concerns\HasPagination;
use Thinktomorrow\Chief\Table\Table\Concerns\HasPresets;
use Thinktomorrow\Chief\Table\Table\Concerns\HasReordering;
use Thinktomorrow\Chief\Table\Table\Concerns\HasRowActions;
use Thinktomorrow\Chief\Table\Table\Concerns\HasRows;
use Thinktomorrow\Chief\Table\Table\Concerns\HasRowViews;
use Thinktomorrow\Chief\Table\Table\Concerns\HasSorters;
use Thinktomorrow\Chief\Table\Table\Concerns\HasTreeStructure;
use Thinktomorrow\Chief\Table\Table\References\HasResourceReference;
use Thinktomorrow\Chief\Table\Table\References\HasTableReference;
use Thinktomorrow\Chief\Table\Table\References\ResourceReference;

class Table extends Component implements Htmlable, Layout
{
    use CanAddQuery;
    use HasActions;
    use HasBulkActions;
    use HasColumns;
    use HasFilters;
    use HasHeaders;
    use HasLivewireComponent;
    use HasLivewireListeners;
    use HasModelKeyName;
    use HasPagination;
    use HasPosition;
    use HasPresets;

    /** Base Query for all table data */
    use HasQuery;

    use HasReordering;

    /** Tree support */
    use HasResourceReference;

    use HasRowActions;
    use HasRows;
    use HasRowViews;
    use HasSorters;
    use HasTableReference;
    use HasTreeStructure;

    protected string $view = 'chief-table::index';

    public static function make()
    {
        return new static;
    }

    public function resource(string $resourceKey): static
    {
        $this->setResourceReference(new ResourceReference($resourceKey));

        if ($this->getResourceReference()->isTreeResource()) {
            $this->returnResultsAsTree();
            $this->addDefaultTreeSorting();
        }

        $modelClassName = $this->getResourceReference()->getResource()->modelClassName();

        $this->modelKeyName((new $modelClassName)->getKeyName());
        $this->setReordering($modelClassName);

        return $this->query(function () use ($modelClassName) {
            return $modelClassName::query();
        });
    }

    public function setReordering(string $modelClass): static
    {
        $model = new $modelClass;

        if ($model instanceof Sortable) {
            $this->allowReordering($model->isSortable());
            $this->setReorderingModelClass($modelClass);
        }

        return $this;
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

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    public function render(): View
    {
        return view($this->getView(), array_merge(parent::data(), [
            'component' => $this,
        ]));
    }

    public function getId(): string
    {
        return $this->getTableReference()->toUniqueString();
    }

    public function getKey(): string
    {
        return $this->getId();
    }
}
