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

            @slot('breadcrumbs')
                @if($model->adminConfig()->getIndexBreadCrumb())
                    <a href="{{ $model->adminConfig()->getIndexBreadCrumb()->url }}" class="link link-primary">
                        <x-chief-icon-label type="back">{{ $model->adminConfig()->getIndexBreadCrumb()->label }}</x-chief-icon-label>
                    </a>
                @endif
            @endslot

            @adminCan('create')
                <a href="@adminRoute('create')" class="btn btn-primary">
                    <x-chief-icon-label type="add">@adminConfig('modelName') toevoegen</x-chief-icon-label>
                </a>
            @endAdminCan
        @endcomponent
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full lg:w-2/3">
                @if(count($models))
                    <x-chief-forms::window>
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
                                    @include($model->adminConfig()->getIndexCardView())
                                @endforeach
                            </div>
                    </x-chief-forms::window>

                    @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
                        {!! $models->links('chief::pagination.default') !!}
                    @endif
                @else
                    @include('chief::manager._index._empty')
                @endif
            </div>

            <div class="w-full space-y-6 lg:w-1/3">
                @if($model->adminConfig()->getIndexSidebar())
                    {!! $model->adminConfig()->getIndexSidebar() !!}
                @endif

                @if($manager->filters()->anyRenderable())
                    <x-chief-forms::window title="Filtering">
                        <form method="GET" class="space-y-6">
                            {!! $manager->filters()->render() !!}

                            <div>
                                <button class="btn btn-primary" type="submit">Filter</button>
                            </div>
                        </form>
                    </x-chief-forms::window>
                @endif

                @adminCan('sort-index', $models->first())
                    <x-chief-forms::window title="Sortering">
                        @if(!$models instanceof Illuminate\Contracts\Pagination\Paginator || !$models->hasPages())
                            <p class="text-grey-700">
                                Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
                            </p>

                            <button data-sortable-toggle class="btn btn-primary">
                                Pas volgorde aan
                            </button>

                            <p class="text-grey-700 font-xs" data-sortable-show-when-sorting>
                                Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
                            </p>
                        @else
                            <p class="text-sm text-grey-700">
                                Deze pagina's worden op de site weergegeven volgens een handmatige sortering.
                            </p>

                            <a href="{{ $manager->route('index-for-sorting') }}" class="btn btn-primary">Sorteer handmatig</a>
                        @endif
                    </x-chief-forms::window>
                @endAdminCan

                @adminCan('archive_index')
                    <x-chief-forms::window title="Sortering">
                        @if(Route::currentRouteName() == 'chief.single.archive_index')
                            <a href="@adminRoute('index')" class="link link-primary">Ga terug naar overzicht</a>
                        @else
                            <a href="@adminRoute('archive_index')" class="link link-warning">Bekijk de gearchiveerde items</a>
                        @endif
                    </x-chief-forms::window>
                @endAdminCan
            </div>
        </div>
    </div>
@stop
