@php
    $title = ucfirst($resource->getIndexTitle());
    $models = collect($resource->getModels());

    $is_archive_index = $is_archive_index ?? false;

    $tableActions = $resource->getTableActions($manager);
    if (! is_array($tableActions)) {
        $tableActions = iterator_to_array($tableActions);
    }
    $tableActionsCount = count($tableActions);

    $tableHeaders = count($models) > 0 ? $resource->getTableHeaders($manager, $models->first()) : [];

    $showOptionsColumn = $manager->can('edit') || $manager->can('preview') || $manager->can('duplicate') || $manager->can('state-update')
@endphp

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="[$resource->getPageBreadCrumb()]"/>
    </x-slot>

    <x-chief::page.grid>
        <div class="container mb-8 space-y-4 max-w-1920">
            <p class="body text-grey-500">
                Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
            </p>

            @adminCan('sort-index', $model)
                <a href="{{ $manager->route('index') }}" title="Sorteer handmatig" class="btn btn-primary">
                    Sortering bewaren
                </a>
            @endAdminCan
        </div>

        <div
            data-sortable
            data-sortable-is-sorting
            data-sortable-endpoint="{{ $manager->route('sort-index') }}"
            data-sortable-id-type="{{ $resource->getSortableType() }}"
            class="divide-y divide-grey-100 border-y border-grey-100"
        >
            @foreach($tree as $node)
                @include('chief-table::nestable.table-node-sort', ['node' => $node, 'level' => 0])
            @endforeach
        </div>

        @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
            {!! $models->links('chief::pagination.default') !!}
        @endif
    </x-chief::page.grid>
</x-chief::page.template>
