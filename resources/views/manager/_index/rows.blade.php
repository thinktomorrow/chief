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
    @include('chief::manager._index._empty')
@endif
