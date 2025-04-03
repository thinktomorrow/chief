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
                    })"
            class="space-y-4"
            wire:loading.delay.class="animate-pulse"
        >
            @include('chief-table::livewire._partials.table-actions')

            <div
                @class([
                    'divide-y divide-grey-100 rounded-xl ring-1 ring-grey-100',
                    'rounded-xl bg-white shadow-md shadow-grey-500/10' => $variant === 'card',
                    '' => $variant === 'transparent',
                ])
            >
                @include('chief-table::livewire._partials.table-container-header')

                <div class="overflow-x-auto whitespace-nowrap">
                    <table class="min-w-full table-fixed divide-y divide-grey-100">
                        <thead>
                            <tr class="*:py-1.5 *:pl-3 [&>*:first-child]:pl-4 [&>*:last-child]:pr-4">
                                {{-- This header contains the checkbox to select/deselect all items. It will only show if bulk actions are available --}}
                                @if ($this->hasAnyBulkActions())
                                    <th scope="col" class="w-5">
                                        <div class="flex items-center">
                                            <x-chief::form.input.checkbox x-ref="tableHeaderCheckbox" />
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
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-grey-100 [&>*:last-child_[data-slot=actions]]:rounded-br-xl">
                            @includeWhen($this->areResultsAsTree() && count($this->getAncestors()) > 0, 'chief-table::rows.ancestor', ['ancestors' => $this->getAncestors()])

                            @if ($this->resultPageCount > 0)
                                @foreach ($results as $item)
                                    @include($this->getRowView(), ['item' => $item])
                                @endforeach
                            @else
                                @include('chief-table::rows.no-results')
                            @endif
                        </tbody>
                    </table>
                </div>

                @include('chief-table::livewire._partials.table-container-footer')
            </div>
        </div>
    @else
        @include('chief-table::index-no-records')
    @endif

    <livewire:chief-form::dialog :parent-id="$this->getId()" />
</div>
