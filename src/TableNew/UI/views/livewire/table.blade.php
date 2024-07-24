@php
    $results = $this->getResults();
    $total = method_exists($results, 'total') ? $results->total() : $results->count();
@endphp

<div
    x-data="{
        selection: [],
        toggleCheckbox(rowKey, checked) {
            if (checked) {
                this.selection.push(rowKey)
            } else {
                this.selection = this.selection.filter((key) => key !== rowKey)
            }

            if (this.selection.length === {{ $results->count() }}) {
                this.$refs.tableHeaderCheckbox.checked = true
                this.$refs.tableHeaderCheckbox.indeterminate = false
            } else if (this.selection.length > 0) {
                this.$refs.tableHeaderCheckbox.checked = false
                this.$refs.tableHeaderCheckbox.indeterminate = true
            } else {
                this.$refs.tableHeaderCheckbox.checked = false
                this.$refs.tableHeaderCheckbox.indeterminate = false
            }
        },
        init() {
            this.$refs.tableHeaderCheckbox.addEventListener('change', (event) => {
                const rows = Array.from(
                    this.$root.querySelectorAll('[data-table-row]'),
                )

                if (event.target.checked) {
                    rows.forEach((row) => {
                        row.querySelector('[data-table-row-checkbox]').checked =
                            true
                        this.selection.push(row.getAttribute('data-table-row'))
                    })
                } else {
                    rows.forEach((row) => {
                        row.querySelector('[data-table-row-checkbox]').checked =
                            false
                    })

                    this.selection = []
                }
            })
        },
    }"
    class="divide-y divide-grey-200 overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-md ring-1 ring-grey-200"
>
    <div class="flex justify-end items-center gap-3 todo-tijs">
        @foreach ($this->getVisibleActions() as $action)
            {{ $action }}
        @endforeach

        @if(count($this->getHiddenActions()) > 0)
            <div>
                <button id="table-hidden-actions" type="button">
                    <x-chief-table-new::button
                        size="sm"
                        color="white"
                        iconRight='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none"> <path d="M11.992 12H12.001" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M11.9842 18H11.9932" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M11.9998 6H12.0088" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                    />
                </button>

                <x-chief::dropdown trigger="#table-hidden-actions" placement="bottom-end">
                    @foreach($this->getHiddenActions() as $action)
                        {{ $action }}
                    @endforeach
                </x-chief::dropdown>
            </div>
        @endif
    </div>

    <div class="space-y-3 px-4 py-3">
        <div class="flex justify-between gap-2" :class="{ 'opacity-50 pointer-events-none': selection.length > 0 }">
            @include('chief-table-new::livewire._partials.filters')
            @include('chief-table-new::livewire._partials.sorters')
        </div>

        @include('chief-table-new::livewire._partials.bulk-actions')
    </div>

    <table class="min-w-full table-fixed divide-y divide-grey-200">
        <thead>
            <tr>
                <th scope="col" class="w-5 py-2 pl-4">
                    <div class="flex items-center">
                        <x-chief::input.checkbox x-ref="tableHeaderCheckbox" />
                    </div>
                </th>

                @foreach ($this->getHeaders() as $header)
                    {{ $header }}
                @endforeach

                <th scope="col" class="py-2 pl-3 pr-4 text-right">
                    <span class="text-sm/5 font-medium text-grey-950">Aanpassen</span>
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-grey-200">
            <tr wire:key="ancestor-row" class="bg-grey-50">
                <td class="relative py-2 pl-4 text-left"></td>
                <td class="relative py-2 pl-3 text-left">
                    <span class="flex gap-1.5 text-sm leading-5 text-grey-700">
                        Tuinmeubelen
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
                            />
                        </svg>
                        Tuinstoel
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
                            />
                        </svg>
                        Eetstoel
                    </span>
                </td>
                <td class="relative py-2 pl-3 text-left"></td>
                <td class="relative py-2 pl-3 text-left"></td>
                <td class="relative py-2 pl-3 pr-4 text-left"></td>
            </tr>

            @foreach ($results as $item)
                {{-- @includeWhen($item->isAncestorRow, 'chief-table-new::rows.ancestor', ['item' => $item]) --}}
                @includeWhen(! $item->isAncestorRow, 'chief-table-new::rows.default', ['item' => $item])
            @endforeach
        </tbody>
    </table>

    @if ($this->hasPagination() && $results->total() > $results->count())
        <div class="px-4 py-2">
            {{ $results->links() }}
        </div>
    @endif
</div>