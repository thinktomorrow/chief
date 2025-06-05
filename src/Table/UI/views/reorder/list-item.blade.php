<div
    x-sortable-item="{{ $item->id }}"
    @class([
        'border border-transparent',
        '[&.table-sort-ghost]:rounded-lg',
        '[&.table-sort-ghost]:border',
        '[&.table-sort-ghost]:border-dashed',
        '[&.table-sort-ghost]:border-primary-500',
        '[&.table-sort-ghost]:bg-primary-50',
        '[&.table-sort-ghost]:shadow',
        '[&.table-sort-ghost]:shadow-primary-50',
        '[&.table-sort-ghost_[x-sortable]]:hidden',
        '[&.table-sort-drag>*]:opacity-50',
        '[&.table-sort-drag>*]:inline-block',
        '[&.table-sort-drag>*]:max-h-28',
        '[&.table-sort-drag>*]:overflow-hidden',
        '[&.table-sort-drag>*]:rounded-md',
        '[&.table-sort-drag>*]:bg-white',
        '[&.table-sort-drag_[data-slot=fade]]:block',
    ])
>
    <div class="relative">
        <div
            x-sortable-handle
            class="group hover:bg-grey-50 inline-flex min-h-6 cursor-grab items-center gap-2 rounded-lg px-3 py-1.5"
        >
            <x-chief::icon.arrow-bend-down-right data-slot="indent-icon" class="text-grey-800 hidden size-5 shrink-0" />

            <div class="flex items-center gap-1">
                <p class="text-grey-400 font-medium">{{ $item->order + 1 }}.</p>
            </div>

            @foreach ($this->getColumns($item) as $column)
                <div class="pointer-events-none flex items-center gap-1">
                    @foreach ($column->getItems() as $columnItem)
                        {{ $columnItem }}
                    @endforeach
                </div>
            @endforeach
        </div>

        <div
            x-sortable
            x-sortable-group="{{ $sortableGroup }}"
            x-sortable-ghost-class="table-sort-ghost"
            x-sortable-drag-class="table-sort-drag"
            class="nested-sortable [&_.nested-sortable]:ml-[28px] [&_[data-slot=indent-icon]]:block"
        >
            @foreach ($item->getChildNodes() as $_item)
                @include(
                    'chief-table::reorder.list-item',
                    [
                        'item' => $_item,
                        'sortableGroup' => $sortableGroup,
                        'indent' => ++$indent,
                    ]
                )
            @endforeach
        </div>

        <div
            data-slot="fade"
            class="absolute top-14 right-0 bottom-0 left-0 hidden bg-gradient-to-b from-transparent to-white/90"
        ></div>
    </div>
</div>
