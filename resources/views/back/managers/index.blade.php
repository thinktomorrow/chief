@extends('chief::back._layouts.master')

@section('page-title')
    @adminLabel('page_title')
@endsection

@section('header')
    @include('chief::back.managers._index._header')
@stop

@section('content')
    <div class="container my">
        <div class="row gutter-6">
            <div class="w-full lg:w-3/4">
                @adminCan('sort-index')
                    <div
                        id="js-sortable"
                        data-sort-route="{{ $manager->route('sort-index') }}"
                        class="row gutter"
                    >
                @elseAdminCan
                    <div class="row gutter">
                @endAdminCan
                    @forelse($models as $model)
                        @include('chief::back.managers._index._card')
                    @empty
                        @include('chief::back.managers._index._empty')
                    @endforelse
                </div>

                @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
                    {!! $models->links() !!}
                @endif
            </div>

            <div class="w-full lg:w-1/4">
                @if($manager->filters()->anyRenderable())
                    <h3>Filtering</h3>

                    <form method="GET">
                        {!! $manager->filters()->render() !!}
                        <div class="stack-xs">
                            <button class="btn btn-primary squished-xs" type="submit">Filter</button>
                        </div>
                    </form>
                @endif

                @adminCan('sort-index')
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
                @endAdminCan

                @adminCan('archive_index')
                    <div class="stack-s">
                        <a href="@adminRoute('archive_index')">Bekijk de gearchiveerde items</a>
                    </div>
                @endAdminCan
            </div>
        </div>
    </div>
@stop
