<?php

namespace Thinktomorrow\Chief\Table\Livewire;

use Livewire\Component;

class TableFiltersComponent extends Component
{
    public $filters;
    public $visibleFilters = [];
    public $hiddenFilters = [];

    public function mount(array $filters)
    {
        $this->filters = $filters;

        foreach ($filters as $filter) {
            $this->visibleFilters[$filter['key']] = $filter;
        }
    }

    public function hideFilter($filterKey)
    {
        $this->hiddenFilters[$filterKey] = $this->visibleFilters[$filterKey];
        unset($this->visibleFilters[$filterKey]);
    }

    public function render()
    {
        return view('chief-table::livewire.table-filters');
    }
}
