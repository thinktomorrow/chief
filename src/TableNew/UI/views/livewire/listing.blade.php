@php
    $results = $this->getModels();
    $indentedResults = [];

    function getIndentedResults($model, $indent = 0, &$indentedResults = [])
    {
        $indentedResults[] = [
            'model' => $model,
            'indent' => $indent,
        ];

        if ($model instanceof \Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable) {
            foreach ($model->getChildren() as $_model) {
                getIndentedResults($_model, $indent + 1, $indentedResults);
            }
        }
    }

    foreach ($results as $model) {
        getIndentedResults($model, 0, $indentedResults);
    }
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
    class="divide-y divide-grey-200 overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-lg ring-1 ring-grey-200"
>
    <div class="space-y-3 px-4 py-3">
        <div class="flex justify-between gap-2" :class="{ 'opacity-50 pointer-events-none': selection.length > 0 }">
            @include('chief-table-new::livewire._partials.filters')
            @include('chief-table-new::livewire._partials.sorting')
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

                <th scope="col" class="py-2 pl-3 text-left">
                    <span class="group inline-flex items-start gap-0.5 text-sm/5 font-medium text-grey-950">
                        Titel
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="size-5 opacity-0 group-hover:opacity-100"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </span>
                </th>

                <th scope="col" class="py-2 pl-3 text-left">
                    <span class="group inline-flex items-start gap-0.5 text-sm/5 font-medium text-grey-950">
                        Status
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="size-5 opacity-0 group-hover:opacity-100"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </span>
                </th>

                <th scope="col" class="py-2 pl-3 text-left">
                    <span class="group inline-flex items-start gap-0.5 text-sm/5 font-medium text-grey-950">
                        Aangepast
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="size-5 opacity-0 group-hover:opacity-100"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </span>
                </th>

                <th scope="col" class="py-2 pl-3 pr-4 text-right">
                    <span class="text-sm/5 font-medium text-grey-950">Aanpassen</span>
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-grey-200">
            @foreach ($indentedResults as $result)
                @php
                    $model = $result['model'];
                    $indent = $result['indent'];
                @endphp

                <tr
                    data-table-row="{{ $this->getRowKey($model) }}"
                    wire:key="{{ $this->getRowKey($model) }}"
                    :class="{ 'bg-grey-50': selection.includes('{{ $this->getRowKey($model) }}') }"
                >
                    <td
                        class="py-2 pl-4 text-left"
                        :class="{ 'relative before:absolute before:block before:top-0 before:bottom-0 before:left-0 before:w-0.5 before:bg-primary-500': selection.includes('{{ $this->getRowKey($model) }}') }"
                    >
                        <div class="flex items-center">
                            <x-chief::input.checkbox
                                data-table-row-checkbox
                                name="{{ $this->getRowKey($model)  }}"
                                id="{{ $this->getRowKey($model)  }}"
                                x-on:change="toggleCheckbox('{{ $this->getRowKey($model) }}', $event.target.checked)"
                            />
                        </div>
                    </td>

                    @foreach ($this->getRow($model) as $column)
                        <td class="py-2 pl-3 text-left">
                            <div class="flex gap-1.5">
                                @if ($loop->first && $indent > 0)
                                    <div class="flex justify-end" style="width: {{ 20 + ($indent - 1) * 26 }}px">
                                        <svg
                                            class="h-5 w-5 text-grey-900"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor"
                                            viewBox="0 0 256 256"
                                        >
                                            <path
                                                d="M229.66,157.66l-48,48a8,8,0,0,1-11.32-11.32L204.69,160H128A104.11,104.11,0,0,1,24,56a8,8,0,0,1,16,0,88.1,88.1,0,0,0,88,88h76.69l-34.35-34.34a8,8,0,0,1,11.32-11.32l48,48A8,8,0,0,1,229.66,157.66Z"
                                            ></path>
                                        </svg>
                                    </div>
                                @endif

                                <span class="leading-5 text-grey-900">
                                    {!! $column !!}
                                </span>
                            </div>
                        </td>
                    @endforeach

                    <td class="py-2 pl-3 pr-4 text-right">
                        <button type="button" class="text-sm/5 font-medium text-primary-500 hover:text-primary-600">
                            Pas aan
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($results->total() > $results->count())
        <div class="px-4 py-2">
            {{ $results->links() }}
        </div>
    @endif
</div>
