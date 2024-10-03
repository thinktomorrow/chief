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

                @foreach ($column->getItems() as $columnItem)
                    {{ $columnItem }}
                @endforeach
            </div>
        </td>
    @endforeach

    <td>
        <div class="flex min-h-6 items-center justify-end gap-1.5">
            <button type="button">
                <x-chief-table::button color="white" size="xs">
                    <x-chief::icon.quill-write />
                </x-chief-table::button>
            </button>
        </div>
    </td>
</tr>
