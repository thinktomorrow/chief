@php
    $results = $this->getReorderResults();
    $sortableGroup = 'reorder-table-item';
@endphp

<div class="space-y-4">

    <div class="flex items-start justify-between gap-4">

        <div class="ml-auto flex items-center justify-end gap-2">
            <x-chief::button size="base" variant="grey" wire:click="stopReordering()">
                <x-chief::icon.sorting />
                <span>Stop met herschikken</span>
            </x-chief::button>
        </div>
    </div>

    <div
        x-sortable
        x-sortable-group="{{ $sortableGroup }}"
        x-sortable-ghost-class="table-sort-ghost"
        x-sortable-drag-class="table-sort-drag"
        x-on:end.stop="(evt) => {

                // reorder within same parent
                if (evt.to === evt.from) {
                    $wire.reorder(evt.target.sortable.toArray());

                    return;
                }

                // reorder to different parent
                const itemId = evt.item.getAttribute('x-sortable-item');
                const parentId = evt.to.closest('[x-sortable-item]')?.getAttribute('x-sortable-item') || null;
                const ids = [...evt.to.children].map(el => el.getAttribute('x-sortable-item'));

                $wire.moveToParent(itemId, parentId, ids);
            }"
        class="overflow-x-auto whitespace-nowrap rounded-xl bg-white px-1 py-2 shadow-md ring-1 ring-grey-200"
    >
        @foreach ($results as $item)
            @include(
                'chief-table::reorder.list-item',
                [
                    'item' => $item,
                    'sortableGroup' => $sortableGroup,
                    'indent' => 0,
                ]
            )
        @endforeach
    </div>
</div>


