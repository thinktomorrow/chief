@php
    $results = $this->getResults();
@endphp

<div>
    @if ($this->hasRecords())
        <div
            x-data="bulkselect({
                        showCheckboxes: @js($this->hasAnyBulkActions() ? true : false),
                        selection: @entangle('bulkSelection'),
                        paginators: @entangle('paginators'),
                        tableHeaderCheckboxSelector: '#table-header-checkbox-{{ $this->getId() }}',
                    })"
            class="space-y-4"
            wire:loading.delay.class="animate-pulse"
        >
            @include('chief-table::livewire._partials.table-actions')

            <x-chief::table :variant="$variant">
                <x-slot name="header">
                    @include('chief-table::livewire._partials.table-container-header')
                </x-slot>

                <x-chief::table.header>
                    {{-- This header contains the checkbox to select/deselect all items. It will only show if bulk actions are available --}}
                    @if ($this->hasAnyBulkActions())
                        <th scope="col" class="w-5">
                            <div class="flex items-center">
                                <x-chief::form.input.checkbox id="table-header-checkbox-{{ $this->getId() }}" />
                            </div>
                        </th>
                    @endif

                    @foreach ($this->getHeaders() as $header)
                        {{ $header }}
                    @endforeach

                    {{-- Empty header for row actions --}}
                    <th x-show="showCheckboxes && selection.length == 0" scope="col"></th>

                    {{-- This header will show when there are items selected, revealing the bulk actions --}}
                    <th
                        x-show="showCheckboxes && selection.length > 0"
                        scope="col"
                        colspan="9999"
                        class="text-left font-normal"
                    >
                        <div class="flex min-h-6 items-center">
                            @include('chief-table::livewire._partials.bulk-actions')
                        </div>
                    </th>
                </x-chief::table.header>

                <x-chief::table.body
                    class="[&>*:last-child_[data-slot=actions]]:rounded-br-xl [&>*:last-child_[data-slot=actions]]:before:bottom-0"
                >
                    @includeWhen($this->areResultsAsTree() && count($this->getAncestors()) > 0, 'chief-table::rows.ancestor', ['ancestors' => $this->getAncestors()])

                    @if ($this->resultPageCount > 0)
                        @foreach ($results as $item)
                            @include($this->getRowView(), ['item' => $item])
                        @endforeach
                    @else
                        @include('chief-table::rows.no-results')
                    @endif
                </x-chief::table.body>

                <x-slot name="footer">
                    @include('chief-table::livewire._partials.table-container-footer')
                </x-slot>
            </x-chief::table>
        </div>
    @else
        @include('chief-table::index-no-records')
    @endif

    <livewire:chief-wire::create-model :parent-component-id="$this->getId()" />
    <livewire:chief-wire::edit-model :parent-component-id="$this->getId()" />
    <livewire:chief-form::dialog :parent-id="$this->getId()" />
</div>
