{{--@php--}}
{{--    $results = $this->getModels();--}}
{{--    $indentedResults = [];--}}

{{--    if(!function_exists('getIndentedResults')) {--}}
{{--        function getIndentedResults($model, $indent = 0, &$indentedResults = [])--}}
{{--        {--}}
{{--            $indentedResults[] = [--}}
{{--                'model' => $model,--}}
{{--                'indent' => $indent,--}}
{{--            ];--}}

{{--            if ($model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable) {--}}
{{--                foreach ($model->getChildren() as $_model) {--}}
{{--                    getIndentedResults($_model, $indent + 1, $indentedResults);--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}
{{--    }--}}

{{--    foreach ($results as $model) {--}}
{{--        getIndentedResults($model, 0, $indentedResults);--}}
{{--    }--}}
{{--@endphp--}}

@php
    $results = $this->getResults();
    $total = method_exists($results, 'total') ? $results->total() : $results->count();
@endphp

{{-- TODO: Divide table into individual components --}}
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
                        row.querySelector('[data-table-row-checkbox]').checked = true
                        this.selection.push(row.getAttribute('data-table-row'))
                    })
                } else {
                    rows.forEach((row) => {
                        row.querySelector('[data-table-row-checkbox]').checked = false
                    })

                    this.selection = []
                }
            })
        },
    }"
    class="divide-y divide-grey-200 overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-lg ring-1 ring-grey-200"
>
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

                @foreach($this->getHeaders() as $header)
                    {{ $header }}
                @endforeach

{{--                <th scope="col" class="py-2 pl-3 text-left">--}}
{{--                    <span class="group inline-flex items-start gap-0.5 text-sm/5 font-medium text-grey-950">--}}
{{--                        Titel--}}
{{--                        <svg--}}
{{--                            xmlns="http://www.w3.org/2000/svg"--}}
{{--                            viewBox="0 0 20 20"--}}
{{--                            fill="currentColor"--}}
{{--                            class="size-5 opacity-0 group-hover:opacity-100"--}}
{{--                        >--}}
{{--                            <path--}}
{{--                                fill-rule="evenodd"--}}
{{--                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"--}}
{{--                                clip-rule="evenodd"--}}
{{--                            />--}}
{{--                        </svg>--}}
{{--                    </span>--}}
{{--                </th>--}}

{{--                <th scope="col" class="py-2 pl-3 text-left">--}}
{{--                    <span class="group inline-flex items-start gap-0.5 text-sm/5 font-medium text-grey-950">--}}
{{--                        Status--}}
{{--                        <svg--}}
{{--                            xmlns="http://www.w3.org/2000/svg"--}}
{{--                            viewBox="0 0 20 20"--}}
{{--                            fill="currentColor"--}}
{{--                            class="size-5 opacity-0 group-hover:opacity-100"--}}
{{--                        >--}}
{{--                            <path--}}
{{--                                fill-rule="evenodd"--}}
{{--                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"--}}
{{--                                clip-rule="evenodd"--}}
{{--                            />--}}
{{--                        </svg>--}}
{{--                    </span>--}}
{{--                </th>--}}

{{--                <th scope="col" class="py-2 pl-3 text-left">--}}
{{--                    <span class="group inline-flex items-start gap-0.5 text-sm/5 font-medium text-grey-950">--}}
{{--                        Aangepast--}}
{{--                        <svg--}}
{{--                            xmlns="http://www.w3.org/2000/svg"--}}
{{--                            viewBox="0 0 20 20"--}}
{{--                            fill="currentColor"--}}
{{--                            class="size-5 opacity-0 group-hover:opacity-100"--}}
{{--                        >--}}
{{--                            <path--}}
{{--                                fill-rule="evenodd"--}}
{{--                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"--}}
{{--                                clip-rule="evenodd"--}}
{{--                            />--}}
{{--                        </svg>--}}
{{--                    </span>--}}
{{--                </th>--}}

                <th scope="col" class="py-2 pl-3 pr-4 text-right">
                    <span class="text-sm/5 font-medium text-grey-950">Aanpassen</span>
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-grey-200">
            @foreach ($results as $item)
                @includeWhen($item->isAncestorRow, 'chief-table-new::rows.ancestor', ['item' => $item])
                @includeWhen(!$item->isAncestorRow, 'chief-table-new::rows.default', ['item' => $item])
            @endforeach
        </tbody>
    </table>

    @if ($this->hasPagination() && $results->total() > $results->count())
        <div class="px-4 py-2">
            {{ $results->links() }}
        </div>
    @endif
</div>
