@php
    $tree = Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree::fromIterable(\App\Models\Resources\Catalog\CatalogPage::with(['tags'])->get());
    $sortableGroup = 'index-table-for-sorting';
@endphp

<div
    x-sortable
    x-sortable-group="{{ $sortableGroup }}"
    x-sortable-ghost-class="sortable-table-row-ghost-class"
    x-sortable-drag-class="sortable-table-row-drag-class"
    class="overflow-x-auto whitespace-nowrap rounded-xl bg-white px-1 py-2 shadow-md ring-1 ring-grey-200"
>
    @foreach ($tree as $branch)
        @include(
            'chief-table::rows.default-for-sorting',
            [
                'item' => $branch,
                'sortableGroup' => $sortableGroup,
                'indent' => 0,
            ]
        )
    @endforeach
</div>
