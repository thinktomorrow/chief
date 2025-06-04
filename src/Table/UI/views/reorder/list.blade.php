@php
    $results = $this->getReorderResults();
    $sortableGroup = 'reorder-table-item';
@endphp

<div
    x-sortable
    x-sortable-group="{{ $sortableGroup }}"
    x-sortable-ghost-class="table-sort-ghost"
    x-sortable-drag-class="table-sort-drag"
    x-on:end.stop="(evt) => {

                // Reordering logic
                console.log(evt.to === evt.from ? 'no move' : 'move');

                // no move needed if target and source are the same
                if (evt.to === evt.from) {
                    console.log(evt.target.sortable.toArray());
                    $wire.reorder(evt.target.sortable.toArray());

                    return;
                }

                const itemId = evt.item.getAttribute('x-sortable-item');
                const parentId = evt.to.closest('[x-sortable-item]')?.getAttribute('x-sortable-item') || null;

                console.log(itemId, parentId, evt.newIndex);
                const ids = [...evt.to.children].map(el => el.getAttribute('x-sortable-item'));
console.log(ids);
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
