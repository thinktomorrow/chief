<x-chief::table.row
    data-table-row="{{ $this->getRowKey($item) }}"
    {{-- The row, especially the checkbox, needs to be reevaluated after sorting/filtering so the alpine reactivity remains intact --}}
    wire:key="{{ $this->getRowKey($item) . '-table-checkbox-' . Str::random() }}"
    x-bind:class="{ 'bg-grey-25 [&_[data-slot=actions]]:bg-grey-25': Array.from(selection).some((item) => item == '{{ $this->getRowKey($item) }}') }"
>
    @if ($this->hasAnyBulkActions())
        <td
            class="align-center relative text-left"
            :class="{ 'before:absolute before:block before:top-0 before:bottom-0 before:left-0 before:w-px before:bg-primary-500': Array.from(selection).some((item) => item == '{{ $this->getRowKey($item) }}') }"
        >
            <div class="flex min-h-6 items-center">
                <x-chief::form.input.checkbox
                    data-table-row-checkbox
                    name="{{ $this->getRowKey($item)  }}"
                    id="{{ $this->getRowKey($item)  }}"
                    x-model="selection"
                    value="{{ $this->getRowKey($item) }}"
                />
            </div>
        </td>
    @endif

    @foreach ($this->getColumns($item) as $column)
        <x-chief::table.cell>
            @if ($loop->first && isset($item->indent) && $item->indent > 0)
                <div class="flex justify-end" style="width: {{ 20 + ($item->indent - 1) * 26 }}px">
                    <x-chief::icon.arrow-bend-down-right class="text-grey-800 size-5" />
                </div>
            @endif

            @foreach ($column->getItems() as $columnItem)
                {{ $columnItem }}
            @endforeach
        </x-chief::table.cell>
    @endforeach

    <td
        data-slot="actions"
        class="align-center sticky right-0 bg-white"
        :class="{ '*:opacity-40 *:pointer-events-none cursor-not-allowed': selection.length > 0 }"
    >
        @include('chief-table::livewire._partials.row-actions')
    </td>
</x-chief::table.row>
