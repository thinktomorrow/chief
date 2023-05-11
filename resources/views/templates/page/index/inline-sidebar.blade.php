{{-- TODO: since the 'showIndexSidebarAside' method was introduced on a resource, this name doesn't make sense anymore. --}}
{{-- This partial - and methods like 'getIndexSidebar' - should be renamed to better reflect its content instead of its layout --}}
@php
    $withFilters = $withFilters ?? true;
@endphp

<div>
    <div class="row-start-start gutter-3">
        @if ($resource->getIndexSidebar())
            <div class="w-full md:w-1/2 2xl:w-1/3">
                {!! $resource->getIndexSidebar() !!}
            </div>
        @endif

        @if ($withFilters)
            <div class="w-full md:w-1/2 2xl:w-1/3">
                @include('chief::manager._index.filter_card')
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
</div>
