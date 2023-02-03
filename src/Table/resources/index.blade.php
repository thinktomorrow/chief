@php
    $title = ucfirst($resource->getIndexTitle());
    $is_archive_index = $is_archive_index ?? false;

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

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="$is_archive_index ? [$resource->getPageBreadCrumb()] : []">
            @if(!$is_archive_index)
                @adminCan('create')
                    <a href="@adminRoute('create')" title="{{ ucfirst($resource->getLabel()) }} toevoegen" class="btn btn-primary">
                        <x-chief::icon-label type="add">{{ ucfirst($resource->getLabel()) }} toevoegen</x-chief::icon-label>
                    </a>
                @endAdminCan
            @endif
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
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

                        <x-chief::table.data>
                            @include('chief::manager._index._options')
                        </x-chief::table.data>
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

        @if ($resource->showIndexSidebarAside())
            <x-slot name="aside">
                @include('chief::templates.page.index.default-sidebar')
            </x-slot>
        @else
            @include('chief::templates.page.index.inline-sidebar', ['withFilters' => false])
        @endif
    </x-chief::page.grid>
</x-chief::page.template>
