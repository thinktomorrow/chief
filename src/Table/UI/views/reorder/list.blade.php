@php
    $results = $this->getReorderResults();
    $sortableGroup = 'reorder-table-item';
@endphp

<div
    x-sortable
    x-sortable-group="{{ $sortableGroup }}"
    x-sortable-ghost-class="table-sort-ghost"
    x-sortable-drag-class="table-sort-drag"
    x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
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
