@if($this->hasAnyFiltersOrSorters())
    <div
        id="table-container-header"
        class="flex justify-between gap-2 px-4 py-2.5"
        :class="{ 'opacity-50 pointer-events-none': selection.length > 0 }"
    >
        @include('chief-table::livewire._partials.filters')
        @include('chief-table::livewire._partials.sorters')
    </div>
@endif
