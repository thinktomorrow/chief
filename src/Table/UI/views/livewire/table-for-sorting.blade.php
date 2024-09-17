@php
    $tree = Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree::fromIterable(\App\Models\Resources\Catalog\CatalogPage::all());
    $sortableGroup = 'index-table-for-sorting';
@endphp

<div
    x-sortable
    x-sortable-group="{{ $sortableGroup }}"
    class="divide-y divide-grey-200 overflow-x-auto whitespace-nowrap rounded-xl bg-white shadow-md ring-1 ring-grey-200"
    style="--indent: 0"
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
