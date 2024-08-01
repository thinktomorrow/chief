@php
    $results = $this->getResults();
    $resultCount = count($results);
    $total = method_exists($results, 'total') ? $results->total() : $results->count();
@endphp

<div>

    <div class="space-y-6">
        <div class="flex items-start justify-between gap-4">
            {{-- TODO(ben): make main actions dynamic --}}
            <div class="todo-ben">
                {{ $this->getFilters()[1]->render() }}
            </div>

            <div class="flex items-center justify-end gap-2">
                @foreach ($this->getVisibleActions() as $action)
                    {{ $action }}
                @endforeach

                @if (count($this->getHiddenActions()) > 0)
                    <div>
                        <button id="table-hidden-actions" type="button">
                            <x-chief-table-new::button
                                color="white"
                                iconRight='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none"> <path d="M13.5 4.5C13.5 3.67157 12.8284 3 12 3C11.1716 3 10.5 3.67157 10.5 4.5C10.5 5.32843 11.1716 6 12 6C12.8284 6 13.5 5.32843 13.5 4.5Z" stroke="currentColor" stroke-width="1.5" /> <path d="M13.5 12C13.5 11.1716 12.8284 10.5 12 10.5C11.1716 10.5 10.5 11.1716 10.5 12C10.5 12.8284 11.1716 13.5 12 13.5C12.8284 13.5 13.5 12.8284 13.5 12Z" stroke="currentColor" stroke-width="1.5" /> <path d="M13.5 19.5C13.5 18.6716 12.8284 18 12 18C11.1716 18 10.5 18.6716 10.5 19.5C10.5 20.3284 11.1716 21 12 21C12.8284 21 13.5 20.3284 13.5 19.5Z" stroke="currentColor" stroke-width="1.5" /> </svg>'
                            />
                        </button>

                        <x-chief::dropdown trigger="#table-hidden-actions" placement="bottom-end">
                            @foreach ($this->getHiddenActions() as $action)
                                {{ $action }}
                            @endforeach
                        </x-chief::dropdown>
                    </div>
                @endif
            </div>
        </div>

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

                    this.storeSelection()
                },
                storeSelection() {
                    $wire.storeBulkSelection(this.selection)
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

                        this.storeSelection()
                    })
                },
            }"
            class="divide-y divide-grey-200 overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-md ring-1 ring-grey-200"
        >
            <div class="space-y-3 px-4 py-3">
                <div
                    class="flex justify-between gap-2"
                    :class="{ 'opacity-50 pointer-events-none': selection.length > 0 }"
                >
                    @include('chief-table-new::livewire._partials.filters')
                    @include('chief-table-new::livewire._partials.sorters')
                </div>
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

                        <th x-show="selection.length == 0" scope="col" class="py-2 pl-3 pr-4"></th>

                        <th
                            x-show="selection.length > 0"
                            scope="col"
                            colspan="9999"
                            class="py-2 pl-3 pr-4 text-left font-normal"
                        >
                            <div class="flex min-h-6 items-center">
                                @include('chief-table-new::livewire._partials.bulk-actions')
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-grey-200">
                    @includeWhen(count($this->getAncestors()) > 0, 'chief-table-new::rows.ancestor', ['ancestors' => $this->getAncestors()])

                    @if ($resultCount > 0)
                        @foreach ($results as $item)
                            @include('chief-table-new::rows.default', ['item' => $item])
                        @endforeach
                    @else
                        @include('chief-table-new::rows.no-results')
                    @endif
                </tbody>
            </table>

            @if ($this->hasPagination() && $results->total() > $resultCount)
                <div class="px-4 py-3">
                    {{ $results->onEachSide(0)->links() }}
                </div>
            @endif
        </div>
    </div>

    <livewire:chief-form::modal :parent-id="$this->getId()" />
</div>
