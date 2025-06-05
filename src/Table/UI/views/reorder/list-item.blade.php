<div
    x-sortable-item="{{ $item->id }}"
    @class([
        '[&.table-sort-ghost]:rounded-md',
        '[&.table-sort-ghost]:border',
        '[&.table-sort-ghost]:border-dashed',
        '[&.table-sort-ghost]:border-primary-500',
        '[&.table-sort-ghost]:bg-primary-50',
        '[&.table-sort-ghost]:shadow',
        '[&.table-sort-ghost]:shadow-primary-50',
        '[&.table-sort-ghost_[x-sortable]]:hidden',
        '[&.table-sort-drag>*]:inline-block',
        '[&.table-sort-drag>*]:max-h-28',
        '[&.table-sort-drag>*]:overflow-hidden',
        '[&.table-sort-drag>*]:rounded-md',
        '[&.table-sort-drag>*]:bg-white',
        '[&.table-sort-drag_[data-slot=fade]]:block',
    ])
>
    <div class="relative">
        <div x-sortable-handle class="group inline-flex min-h-6 cursor-pointer items-center gap-2 px-2 py-1">
            <x-chief::icon.drag-drop-arrows class="size-5 shrink-0 text-grey-300 group-hover:text-grey-800" />
            <x-chief::icon.arrow-bend-down-right data-slot="indent-icon" class="hidden size-5 shrink-0 text-grey-800" />

            <div class="flex items-center gap-1">
                {{ $item->order + 1 }}.
            </div>

            @foreach ($this->getColumns($item) as $column)
                <div class="flex items-center gap-1">
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
            class="absolute bottom-0 left-0 right-0 top-14 hidden bg-gradient-to-b from-transparent to-white/90"
        ></div>
    </div>
</div>
