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

            <div class="w-full lg:w-1/3 space-y-6">
                @if($manager->filters()->anyRenderable())
                    <div class="window window-grey">
                        <span class="text-xl font-semibold text-grey-900">Filtering</span>

                        <form method="GET">
                            {!! $manager->filters()->render() !!}

                            <button class="btn btn-primary" type="submit">Filter</button>
                        </form>
                    </div>
                @endif

                @adminCan('sort-index', $models->first())
                    <div class="window window-grey space-y-6">
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
                                <p class="text-grey-700 text-sm">
                                    Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
                                </p>

                                <a href="{{ $manager->route('index-for-sorting') }}" class="btn btn-primary">Sorteer handmatig</a>
                            @endif
                        </div>
                    </div>
                @endAdminCan

                @adminCan('archive_index')
                    <div class="window window-grey">
                        <a href="@adminRoute('archive_index')" class="link link-warning">Bekijk de gearchiveerde items</a>
                    </div>
                @endAdminCan
            </div>
        </div>
    </div>
@stop
