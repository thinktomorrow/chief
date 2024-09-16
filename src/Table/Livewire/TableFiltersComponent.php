<?php

namespace Thinktomorrow\Chief\Table\Livewire;

use Livewire\Component;

class TableFiltersComponent extends Component
{
    public $filters;
    public $containerFilters = [];
    public $drawerFilters = [];

    public function mount(array $filters)
    {
        $this->filters = $filters;

        foreach ($filters as $filter) {
            $this->containerFilters[$filter['key']] = $filter;
        }
    }

    public function moveFilterToDrawer($filterKey)
    {
        $this->drawerFilters[$filterKey] = $this->containerFilters[$filterKey];
        unset($this->containerFilters[$filterKey]);
    }

    public function render()
    {
        return view('chief-table::livewire.table-filters');
    }
}
