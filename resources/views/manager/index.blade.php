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
                        @adminCan('sort-index', $models->first())
                            <div
                                id="js-sortable"
                                data-sort-route="{{ $manager->route('sort-index') }}"
                                class="relative -m-8 divide-y divide-grey-100"
                            >
                        @elseAdminCan
                            <div class="relative -m-8 divide-y divide-grey-100">
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

            <div class="w-full space-y-6 lg:w-1/3">
                @if($manager->filters()->anyRenderable())
                    <div class="window window-grey space-y-6">
                        <span class="text-xl font-semibold text-grey-900">Filtering</span>

                        <form method="GET" class="space-y-6">

                            {!! $manager->filters()->render() !!}

                            <button class="btn btn-primary mt-4" type="submit">Filter</button>
                        </form>
                    </div>
                @endif

                @adminCan('sort-index', $models->first())
                    <div class="space-y-6 window window-grey">
                        <div class="space-y-4">
                            <span class="text-xl font-semibold text-grey-900">Sortering</span>

                            @if(!$models instanceof Illuminate\Contracts\Pagination\Paginator || !$models->hasPages())
                                <p class="text-grey-700">
                                    Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
                                </p>

                                <button data-sortable-toggle class="btn btn-primary">Sorteer handmatig</button>

                                <p class="text-grey-700 font-xs" data-sortable-show-when-sorting>
                                    Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
                                </p>
                            @else
                                <p class="text-sm text-grey-700">
                                    Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
                                </p>

                                <a href="{{ $manager->route('index-for-sorting') }}" class="btn btn-primary">Sorteer handmatig</a>
                            @endif
                        </div>
                    </div>
                @endAdminCan

                @adminCan('archive_index')
                    <div class="window window-grey">
                        @if(Route::currentRouteName() == 'chief.single.archive_index')
                            <a href="@adminRoute('index')" class="link link-primary">Ga terug naar overzicht</a>
                        @else
                            <a href="@adminRoute('archive_index')" class="link link-warning">Bekijk de gearchiveerde items</a>
                        @endif
                    </div>
                @endAdminCan
            </div>
        </div>
    </div>
@stop
