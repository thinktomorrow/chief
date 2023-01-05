@php
    $tableActions = $resource->getTableActions($manager);
    if (! is_array($tableActions)) {
        $tableActions = iterator_to_array($tableActions);
    }
    $tableActionsCount = count($tableActions);

    $tableHeaders = count($models) > 0 ? $resource->getTableHeaders($manager, $models->first()) : [];

    $bodyAttributes = $manager->can('sort-index', $models->first())
        ? 'data-sortable data-sortable-endpoint=' . $manager->route('sort-index') .' data-sortable-id-type='. $resource->getSortableType()
        : '';
@endphp

<x-chief::index sidebar="{{ $resource->showIndexSidebarAside() }}">

    @include('chief::manager._index.archived_breadcrumbs')

    <x-chief::table
        :filters="(!$resource->showIndexSidebarAside() ? $manager->filters()->all() : [])"
        :sticky="$resource->displayTableHeaderAsSticky()"
        body-attributes="{{ $bodyAttributes }}"
    >
        @if ($tableActionsCount > 0)
            <x-slot name="actions">
                @foreach ($tableActions as $bulkAction)
                    {{ $bulkAction->render() }}
                @endforeach
            </x-slot>
        @endif

        <x-slot name="header">
            @if ($tableActionsCount > 0)
                <x-chief::table.header>
                    <input
                        data-bulk-all-checkbox
                        type="checkbox"
                        name="bulk_all"
                        id="bulk_all"
                        class="with-custom-checkbox"
                    >
                </x-chief::table.header>
            @endif

            @foreach ($tableHeaders as $tableHead)
                {{ $tableHead->render() }}
            @endforeach

            @adminCan('edit')
                <x-chief::table.header/>
            @endAdminCan
        </x-slot>

        <x-slot name="body">
            @forelse ($models as $model)
                <x-chief::table.row data-sortable-handle data-sortable-id="{{ $resource->getTableRowId($model) }}">
                    @if($tableActionsCount > 0)
                        <x-chief::table.data>
                            <input
                                data-bulk-item-checkbox
                                type="checkbox"
                                name="bulk_items[]"
                                id="item_{{ $loop->index }}"
                                class="with-custom-checkbox"
                                value="{{ $resource->getTableRowId($model) }}"
                            >
                        </x-chief::table.data>
                    @endif

                    @foreach ($resource->getTableRow($manager, $model) as $tableCell)
                        {{ $tableCell->render() }}
                    @endforeach

                    @adminCan('edit')
                        <x-chief::table.data class="text-right">
                            <div data-sortable-hide-when-sorting>
                                @include('chief::manager._index._options')
                            </div>
                        </x-chief::table.data>
                    @endAdminCan
                </x-chief::table.row>
            @empty
                <x-chief::table.row>
                    <x-chief::table.data colspan="100%" class="text-center">
                        Geen {{ $resource->getIndexTitle() }} gevonden
                    </x-chief::table.data>
                </x-chief::table.row>
            @endforelse
        </x-slot>
    </x-chief::table>

    @if ($models instanceof \Illuminate\Contracts\Pagination\Paginator)
        {!! $models->links('chief::pagination.default') !!}
    @endif

    {{-- TODO: avoid duplication of sidebar code ... --}}
    @if (!$resource->showIndexSidebarAside())
        <div class="row-start-start gutter-3">
            @if ($resource->getIndexSidebar())
                <div class="w-full md:w-1/2 2xl:w-1/3">
                    {!! $resource->getIndexSidebar() !!}
                </div>
            @endif

            @adminCan('sort-index', $models->first())
                <div class="w-full md:w-1/2 2xl:w-1/3">
                    @include('chief::manager._index.sort_card')
                </div>
            @endAdminCan


            @adminCan('archive_index')
                <div class="w-full md:w-1/2 2xl:w-1/3">
                    @include('chief::manager._index.archive_card')
                </div>
            @endAdminCan
        </div>
    @endif
</x-chief::index>
