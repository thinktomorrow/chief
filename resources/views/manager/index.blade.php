@php
    $title = ucfirst($resource->getIndexTitle());
    $is_archive_index = $is_archive_index ?? false;
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
        @if(count($models))
            <div class="card">
                @adminCan('sort-index', $models->first())
                    <div
                        data-sortable
                        data-sortable-endpoint="{{ $manager->route('sort-index') }}"
                        data-sortable-id-type="{{ $resource->getSortableType() }}"
                        class="-my-4 divide-y divide-grey-100"
                    >
                @elseAdminCan
                    <div class="-my-4 divide-y divide-grey-100">
                @endAdminCan
                        @foreach($models as $model)
                            @include($resource->getIndexCardView())
                        @endforeach
                    </div>
            </div>

            @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
                {!! $models->links('chief::pagination.default') !!}
            @endif
        @else
            <div class="card">
                <p class="text-grey-500">Geen resultaten gevonden.</p>
            </div>
        @endif

        @if ($resource->showIndexSidebarAside())
            <x-slot name="aside">
                @include('chief::templates.page.index.default-sidebar')
            </x-slot>
        @else
            @include('chief::templates.page.index.inline-sidebar')
        @endif
    </x-chief::page.grid>
</x-chief::page.template>
