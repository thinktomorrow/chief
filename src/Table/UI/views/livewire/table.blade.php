@php
    $results = $this->getResults();
@endphp

<div>
    <div class="space-y-6">
        <div class="flex items-start justify-between gap-4">
            {{-- TODO(ben): make main actions dynamic --}}
            <div class="todo-ben">
                {{-- {{ $this->getFilters()[1]->render() }} --}}
            </div>

            <div class="flex items-center justify-end gap-2">
                @foreach ($this->getVisibleActions() as $action)
                    {{ $action }}
                @endforeach

                @if (count($this->getHiddenActions()) > 0)
                    <div>
                        <button
                            type="button"
                            x-on:click="$dispatch('open-dialog', { 'id': 'table-hidden-actions' })"
                        >
                            <x-chief-table::button
                                color="white"
                                iconRight='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none"> <path d="M13.5 4.5C13.5 3.67157 12.8284 3 12 3C11.1716 3 10.5 3.67157 10.5 4.5C10.5 5.32843 11.1716 6 12 6C12.8284 6 13.5 5.32843 13.5 4.5Z" stroke="currentColor" stroke-width="1.5" /> <path d="M13.5 12C13.5 11.1716 12.8284 10.5 12 10.5C11.1716 10.5 10.5 11.1716 10.5 12C10.5 12.8284 11.1716 13.5 12 13.5C12.8284 13.5 13.5 12.8284 13.5 12Z" stroke="currentColor" stroke-width="1.5" /> <path d="M13.5 19.5C13.5 18.6716 12.8284 18 12 18C11.1716 18 10.5 18.6716 10.5 19.5C10.5 20.3284 11.1716 21 12 21C12.8284 21 13.5 20.3284 13.5 19.5Z" stroke="currentColor" stroke-width="1.5" /> </svg>'
                            />
                        </button>

                        <x-chief::dialog.dropdown id="table-hidden-actions" placement="bottom-end">
                            @foreach ($this->getHiddenActions() as $action)
                                {{ $action }}
                            @endforeach
                        </x-chief::dialog.dropdown>
                    </div>
                @endif
            </div>
        </div>

        <div
            x-data="{
                showCheckboxes: {{ $this->hasAnyBulkActions() ? 'true' : 'false' }},
                selection: @entangle('bulkSelection'),
                paginators: @entangle('paginators'),
                maxItemCount: @entangle('resultTotal'),
                maxItemCountOnCurrentPage: @entangle('resultPageCount'),
                isAllSelectedOnPage: false,
                isIndeterminateOnPage: false, // One or more but not all selected on page
                init() {

                    // No longer header checkbox work after filtering
                    // when total changed after filtering does not work ... best to entangle??

                    this.$refs.tableHeaderCheckbox.addEventListener('change', (event) => {
                        if (event.target.checked) {
                            const checkboxes = document.querySelectorAll('[data-table-row-checkbox]');

                            // Merge with current selection and make sure they are unique
                            this.selection = [...this.selection, ...Array.from(checkboxes).map((checkbox) => checkbox.value)].filter((value, index, self) => self.indexOf(value) === index);
                        } else {

                            // Remove all items from current page from selection
                            this.selection = this.selection.filter((item) => !this.pageItems.some((pageItem) => pageItem == item));
                        }
                    })

                    $watch('selection', (selection) => {
                        this.evaluateHeaderCheckboxState();
                    })

                    $watch('isIndeterminateOnPage', (value) => {
                    console.log('indeterminate on page', value);
                        this.$refs.tableHeaderCheckbox.indeterminate = value
                    });

                    $watch('isAllSelectedOnPage', (value) => {
                    console.log('all selected on page', value);
                        this.$refs.tableHeaderCheckbox.checked = value;
                    });

                    $watch('paginators', (value) => {
                        console.log('paginators', value);
                        this.$nextTick(() => {
                            this.setPageItems();
                            this.evaluateHeaderCheckboxState();
                        });
                    });

                    // On initial load
                    this.$nextTick(() => {
                        this.setPageItems();
                        this.evaluateHeaderCheckboxState();
                    });
                },
                getPageItems() {
                    return this.pageItems;
                },
                setPageItems() {
                    this.pageItems = Array.from(this.$el.querySelectorAll('[data-table-row-checkbox]')).map((checkbox) => checkbox.value);
                },
                getSelectedPageItems() {
                    return this.pageItems.filter((item) => this.selection.some((selectedItem) => selectedItem == item));
                },
                evaluateHeaderCheckboxState(){

                    const pageItems = this.getPageItems();
                    const selectedPageItems = this.getSelectedPageItems();
                    console.log(this.selection, pageItems, selectedPageItems);
                    if(pageItems.every((item) => this.selection.some((selectedItem) => selectedItem == item))) {
                        console.log('eval: all selected on page');
                            this.isAllSelectedOnPage = true;
                        } else {
                        console.log('eval: not all selected on page');
                            this.isAllSelectedOnPage = false;
                        }

                        if(selectedPageItems.length === pageItems.length || selectedPageItems.length == 0) {
                        console.log('indeterminate false');
                            this.isIndeterminateOnPage = false;
                        } else {
                        console.log('indeterminate true');
                            this.isIndeterminateOnPage = true;
                        }
                }
            }"
            class="divide-y divide-grey-200 overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-md ring-1 ring-grey-200"
        >
            <div
                id="table-header"
                class="flex justify-between gap-2 px-4 py-3"
                :class="{ 'opacity-50 pointer-events-none': selection.length > 0 }"
            >
                @include('chief-table::livewire._partials.filters')
                @include('chief-table::livewire._partials.sorters')
            </div>

            <table class="min-w-full table-fixed divide-y divide-grey-200">
                <thead>
                    <tr>
                        <th x-show="showCheckboxes" scope="col" class="w-5 py-2 pl-4">
                            <div class="flex items-center">
                                <x-chief::input.checkbox x-ref="tableHeaderCheckbox" />
                            </div>
                        </th>

                        @foreach ($this->getHeaders() as $header)
                            {{ $header }}
                        @endforeach

                        <th x-show="showCheckboxes && selection.length == 0" scope="col" class="py-2 pl-3 pr-4"></th>

                        <th
                            x-show="showCheckboxes && selection.length > 0"
                            scope="col"
                            colspan="9999"
                            class="py-2 pl-3 pr-4 text-left font-normal"
                        >
                            <div class="flex min-h-6 items-center">
                                @include('chief-table::livewire._partials.bulk-actions')
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-grey-200">
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

            @if ($this->hasPagination() && $this->resultTotal > $this->resultPageCount)
                <div class="px-4 py-3">
                    {{ $results->onEachSide(0)->links() }}
                </div>
            @endif
        </div>

        <livewire:chief-form::dialog :parent-id="$this->getId()" />
    </div>
</div>
