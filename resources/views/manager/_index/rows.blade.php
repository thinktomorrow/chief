@if(count($models))
    <div class="card">
        @adminCan('sort-index', $models->first())
            <div
                id="js-sortable"
                data-sort-route="{{ $manager->route('sort-index') }}"
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
