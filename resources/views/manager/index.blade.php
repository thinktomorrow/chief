@extends('chief::layout.master')

@section('page-title')
    @adminConfig('indexTitle')
@endsection

@section('header')
    <div class="container">
        @component('chief::layout._partials.header')
            @slot('title')
                @adminConfig('indexTitle')
            @endslot

            @adminCan('create')
                <a href="@adminRoute('create')" class="btn btn-primary">
                    <x-icon-label type="add">@adminConfig('modelName') toevoegen</x-icon-label>
                </a>
            @endAdminCan
        @endcomponent
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full lg:w-2/3">
                @if($models->count())
                    <div class="window window-white">
                        @adminCan('sort-index')
                            <div
                                id="js-sortable"
                                data-sort-route="{{ $manager->route('sort-index') }}"
                                class="relative divide-y divide-grey-100 -mx-8 -my-6"
                            >
                        @elseAdminCan
                            <div class="relative divide-y divide-grey-100 -mx-8 -my-6">
                        @endAdminCan
                                @foreach($models as $model)
                                    @include('chief::manager._index._card')
                                @endforeach
                            </div>
                    </div>
                    @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
                        {!! $models->links() !!}
                    @endif
                @else
                    @include('chief::manager._index._empty')
                @endif
            </div>

            <div class="w-full lg:w-1/3">
                @if($manager->filters()->anyRenderable())
                    <div class="window window-grey">
                        <h3>Filtering</h3>

                        <form method="GET">
                            {!! $manager->filters()->render() !!}
                            <div class="stack-xs">
                                <button class="btn btn-primary squished-xs" type="submit">Filter</button>
                            </div>
                        </form>
                    </div>
                @endif

                @adminCan('sort-index', $models->first())
                    <div class="window window-grey mb-4">
                        @if(!$models instanceof Illuminate\Contracts\Pagination\Paginator || !$models->hasPages())
                            <div class="mb-4">
                                <p class="mb-4">Deze pagina's worden op de site weergegeven volgens een handmatige sortering.</p>
                                <button class="btn btn-primary " data-sortable-toggle>Sorteer handmatig</button>
                                <p class="font-xs mt-2" data-sortable-show-when-sorting>Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.</p>
                            </div>
                        @else
                            <div class="mb-4">
                                <p class="mb-4">Deze pagina's worden op de site weergegeven volgens een handmatige sortering.</p>
                                <a class="btn btn-primary" href="{{ $manager->route('index-for-sorting') }}">Sorteer handmatig</a>
                            </div>
                        @endif
                    </div>
                @endAdminCan

                @adminCan('archive_index')
                    <div class="window window-grey">
                        <div class="stack-s">
                            <a href="@adminRoute('archive_index')">Bekijk de gearchiveerde items</a>
                        </div>
                    </div>
                @endAdminCan
            </div>
        </div>
    </div>
@stop
