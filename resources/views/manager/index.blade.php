@php
    $title = ucfirst($resource->getIndexTitle());
    $is_archive_index = $is_archive_index ?? false;
@endphp

<x-chief::template :title="$title">
    <x-slot name="hero">
        <x-chief::template.hero :title="$title" :breadcrumbs="$is_archive_index ? [$resource->getPageBreadCrumb()] : []">
            @if(!$is_archive_index)
                @adminCan('create')
                    <a href="@adminRoute('create')" title="{{ ucfirst($resource->getLabel()) }} toevoegen" class="btn btn-primary">
                        <x-chief-icon-label type="add">{{ ucfirst($resource->getLabel()) }} toevoegen</x-chief-icon-label>
                    </a>
                @endAdminCan
            @endif
        </x-chief::template.hero>
    </x-slot>

    <x-chief::template.grid>
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
                @include('chief::template.index.default-sidebar')
            </x-slot>
        @else
            @include('chief::template.index.inline-sidebar')
        @endif
    </x-chief::template.grid>
</x-chief::template>
