@if($resource->getIndexSidebar())
    {!! $resource->getIndexSidebar() !!}
@endif

@include('chief::manager._index.filter_card')

@adminCan('sort-index', $model)
    @include('chief::manager._index.sort_card')
@endAdminCan

@adminCan('archive_index')
    @include('chief::manager._index.archive_card')
@endAdminCan
