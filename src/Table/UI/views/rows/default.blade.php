<tr
    data-table-row="{{ $this->getRowKey($item) }}"
    x-key="{{ $this->getRowKey($item) }}"
    wire:key="{{ $this->getRowKey($item) }}"
    :class="{ 'bg-grey-50': Array.from(selection).some((item) => item == '{{ $this->getRowKey($item) }}') }"
    class="*:py-1.5 *:pl-3 [&>*:first-child]:pl-4 [&>*:last-child]:pr-4"
>
    <td
        x-show="showCheckboxes"
        class="relative text-left"
        :class="{ 'before:absolute before:block before:top-0 before:bottom-0 before:left-0 before:w-px before:bg-primary-500': Array.from(selection).some((item) => item == '{{ $this->getRowKey($item) }}') }"
    >
        <div class="flex min-h-6 items-center">
            <x-chief::input.checkbox
                data-table-row-checkbox
                name="{{ $this->getRowKey($item)  }}"
                id="{{ $this->getRowKey($item)  }}"
                x-model="selection"
                value="{{ $this->getRowKey($item) }}"
            />
        </div>
    </td>

    @foreach ($this->getColumns($item) as $column)
        <td class="text-left">
            <div class="flex min-h-6 items-center gap-1.5">
                @if ($loop->first && isset($item->indent) && $item->indent > 0)
                    <div class="flex justify-end" style="width: {{ 20 + ($item->indent - 1) * 26 }}px">
                        <svg class="size-5 text-grey-800">
                            <use xlink:href="#icon-indent"></use>
                        </svg>
                    </div>
                @endif

                @foreach ($column->getItems() as $item)
                    {{ $item }}
                @endforeach
            </div>
        </td>
    @endforeach

    <td>
        <div class="flex min-h-6 items-center justify-end gap-1.5">
            <button type="button">
                <x-chief-table::button
                    color="white"
                    size="xs"
                    icon-left='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M5.07579 17C4.08939 4.54502 12.9123 1.0121 19.9734 2.22417C20.2585 6.35185 18.2389 7.89748 14.3926 8.61125C15.1353 9.38731 16.4477 10.3639 16.3061 11.5847C16.2054 12.4534 15.6154 12.8797 14.4355 13.7322C11.8497 15.6004 8.85421 16.7785 5.07579 17Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M4 22C4 15.5 7.84848 12.1818 10.5 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                />
            </button>
        </div>
    </td>
</tr>
