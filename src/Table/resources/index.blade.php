@php
    use Illuminate\Contracts\Pagination\Paginator;$title = ucfirst($resource->getIndexTitle());
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

    $showOptionsColumn = $resource->showIndexOptionsColumn() && ($manager->can('edit') || $manager->can('preview') || $manager->can('duplicate') || $manager->can('state-update'));
@endphp

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="$is_archive_index ? [$resource->getPageBreadCrumb()] : []">
            @if($resource->getIndexDescription())
                <x-slot name="description">
                    {{ $resource->getIndexDescription() }}
                </x-slot>
            @endif

            @if($resource->getIndexHeaderContent())
                {!! $resource->getIndexHeaderContent() !!}
            @endif

            @if(!$is_archive_index)
                @include('chief::manager._index._index_actions')
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
                    <x-chief::table.header class="form-light">
                        <x-chief::input.checkbox data-bulk-all-checkbox name="bulk_all" id="bulk_all"/>
                    </x-chief::table.header>
                @endif

                @foreach ($tableHeaders as $tableHead)
                    {{ $tableHead->render() }}
                @endforeach

                @if ($showOptionsColumn)
                    <x-chief::table.header/>
                @endif
            </x-slot>

            <x-slot name="body">
                @forelse ($models as $model)
                    <x-chief::table.row data-sortable-handle data-sortable-id="{{ $resource->getTableRowId($model) }}">
                        @if($tableActionsCount > 0)
                            <x-chief::table.data class="form-light">
                                <x-chief::input.checkbox
                                    data-bulk-item-checkbox
                                    id="item_{{ $loop->index }}"
                                    name="bulk_items[]"
                                    value="{{ $resource->getTableRowId($model) }}"
                                />
                            </x-chief::table.data>
                        @endif

                        @foreach ($resource->getTableRow($manager, $model) as $tableCell)
                            {{ $tableCell->render() }}
                        @endforeach

                        @if ($showOptionsColumn)
                            <x-chief::table.data>
                                @include('chief::manager._index._options')
                            </x-chief::table.data>
                        @endif
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

        @if ($models instanceof Paginator)
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
