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

<div
    class="divide-y divide-bui-grey-200 overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-lg ring-1 ring-bui-grey-200"
>
    <table
        x-data="{
            selected: [],
            toggleSelection(rowKey, checked) {
                if (checked) {
                    this.selected.push(rowKey)
                } else {
                    this.selected = this.selected.filter((key) => key !== rowKey)
                }

                if (this.selected.length === {{ $results->count() }}) {
                    this.$refs.checkbox_all.checked = true
                    this.$refs.checkbox_all.indeterminate = false
                } else if (this.selected.length > 0) {
                    this.$refs.checkbox_all.checked = false
                    this.$refs.checkbox_all.indeterminate = true
                } else {
                    this.$refs.checkbox_all.checked = false
                    this.$refs.checkbox_all.indeterminate = false
                }
            },
            init() {
                this.$refs.checkbox_all.addEventListener('change', (event) => {
                    const rows = Array.from(
                        this.$root.querySelectorAll('[data-table-row]'),
                    )

                    if (event.target.checked) {
                        rows.forEach((row) => {
                            row.querySelector('[data-table-row-checkbox]').checked =
                                true

                            this.selected.push(row.getAttribute('data-table-row'))
                        })
                    } else {
                        rows.forEach((row) => {
                            row.querySelector('[data-table-row-checkbox]').checked =
                                false
                        })

                        this.selected = []
                    }
                })
            },
        }"
        class="min-w-full table-fixed divide-y divide-bui-grey-200"
    >
        <thead>
            <tr>
                <th scope="col" class="w-5 py-2 pl-4">
                    <div class="flex items-center">
                        <x-chief::input.checkbox x-ref="checkbox_all" />
                    </div>
                </th>

                <th scope="col" class="py-2 pl-3 text-left">
                    <span class="text-sm/5 font-medium text-bui-grey-950">Titel</span>
                </th>

                <th scope="col" class="py-2 pl-3 text-left">
                    <span class="text-sm/5 font-medium text-bui-grey-950">Status</span>
                </th>

                <th scope="col" class="py-2 pl-3 text-left">
                    <span class="text-sm/5 font-medium text-bui-grey-950">Aangepast</span>
                </th>

                <th scope="col" class="py-2 pl-3 pr-4 text-right">
                    <span class="text-sm/5 font-medium text-bui-grey-950">Aanpassen</span>
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-bui-grey-200">
            @foreach ($indentedResults as $result)
                @php
                    $model = $result['model'];
                    $indent = $result['indent'];
                @endphp

                <tr
                    data-table-row="{{ $this->getRowKey($model) }}"
                    wire:key="{{ $this->getRowKey($model) }}"
                    :class="{ 'bg-bui-grey-50': selected.includes('{{ $this->getRowKey($model) }}') }"
                >
                    <td class="w-5 py-2 pl-4 text-left">
                        <div class="flex items-center">
                            <x-chief::input.checkbox
                                data-table-row-checkbox
                                name="{{ $this->getRowKey($model)  }}"
                                id="{{ $this->getRowKey($model)  }}"
                                x-on:change="toggleSelection('{{ $this->getRowKey($model) }}', $event.target.checked)"
                            />
                        </div>
                    </td>

                    @foreach ($this->getRow($model) as $column)
                        <td class="py-2 pl-3 text-left">
                            <div class="flex gap-1.5">
                                @if ($loop->first && $indent > 0)
                                    <div class="flex justify-end" style="width: {{ 20 + ($indent - 1) * 26 }}px">
                                        <svg
                                            class="h-5 w-5 text-bui-grey-900"
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

                                <span class="leading-5 text-bui-grey-900">
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
