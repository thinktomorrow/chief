@extends('chief::back._layouts.master')

@section('page-title')
    @adminConfig('indexTitle')
@endsection

@section('header')
    <div class="container">
        @component('chief::back._layouts._partials.header')
            @slot('title')
                @adminConfig('indexTitle')
            @endslot

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-link-label type="back">Dashboard</x-link-label>
                </a>
            @endslot

            @adminCan('create')
                <a href="@adminRoute('create')" class="btn btn-primary">
                    <x-link-label type="add">Voeg een @adminConfig('modelName') toe</x-link-label>
                </a>
            @endAdminCan
        @endcomponent
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row gutter-6">
            <div class="w-full lg:w-2/3">
                @if($models->count())
                    <div class="window window-white">
                        @adminCan('sort-index')
                            <div
                                id="js-sortable"
                                data-sort-route="{{ $manager->route('sort-index') }}"
                                class="relative divide-y divide-grey-200 border-t border-b border-grey-200 -m-12"
                            >
                        @elseAdminCan
                            <div class="relative divide-y divide-grey-200 -m-12">
                        @endAdminCan
                                @foreach($models as $model)
                                    @include('chief::back.managers._index._card')
                                @endforeach
                            </div>
                        @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
                            {!! $models->links() !!}
                        @endif
                    </div>
                @else
                    @include('chief::back.managers._index._empty')
                @endif
            </div>

            <div class="w-full lg:w-1/3">
                {{-- TODO: show relevant content --}}
                <div class="window window-grey">
                    <p class="text-grey-700">Placeholder</p>
                </div>

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

                @adminCan('sort-index')
                    <div class="window window-grey">
                        @if(!$models instanceof Illuminate\Contracts\Pagination\Paginator || !$models->hasPages())
                            <div class="mb-8">
                                <p class="mb-4">Deze pagina's worden op de site weergegeven volgens een handmatige sortering.</p>
                                <button class="btn btn-primary " data-sortable-toggle>Sorteer handmatig</button>
                                <p class="font-xs mt-2" data-sortable-show-when-sorting>Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.</p>
                            </div>
                        @else
                            <div class="mb-8">
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
