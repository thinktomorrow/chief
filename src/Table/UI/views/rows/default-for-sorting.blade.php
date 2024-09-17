@php
    $columns = $this->getColumns($item);
@endphp

<div
    x-sortable-item="{{ $item->id }}"
    x-sortable-handle
    @class(['border-t border-grey-200', 'first:border-t-0' => $indent === 0])
>
    @if (count($columns) > 0)
        <div class="flex gap-2 px-3 py-1.5">
            @foreach ($columns as $column)
                <div class="flex w-64 gap-2">
                    @if ($loop->first)
                        @if ($indent > 0)
                            <div
                                class="sortable-table-row-indent-class flex justify-end"
                                style="width: calc(var(--indent) * 26px)"
                            >
                                <svg
                                    class="h-5 w-5 text-grey-800"
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
                    @endif

                    <div class="flex min-h-6 items-center gap-1.5">
                        @foreach ($column->getItems() as $columnItem)
                            {{ $columnItem }}
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @php
        $indent++;
    @endphp

    <div
        x-sortable
        x-sortable-group="{{ $sortableGroup }}"
        class="divide-y divide-grey-200"
        style="--indent: {{ $indent }}"
    >
        @foreach ($item->getChildNodes() as $_item)
            @include(
                'chief-table::rows.default-for-sorting',
                [
                    'item' => $_item,
                    'sortableGroup' => $sortableGroup,
                    'indent' => $indent,
                ]
            )
        @endforeach
    </div>
</div>
