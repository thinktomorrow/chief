@extends('chief::back._layouts.master')

@section('page-title')
    @adminLabel('page_title')
@endsection

@section('header')
    @include('chief::back.managers._index._header')
@stop

@section('content')

    <div class="row gutter-l stack">
        <div class="column">
            <div class="row gutter-s">
                @forelse($models as $model)
                    @include('chief::back.managers._index._card')
                @empty
                    @include('chief::back.managers._index._empty')
                @endforelse
            </div>

            @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
                {!! $models->links('chief::back.managers.pagination') !!}
            @endif

        </div>

        <div class="column-3">
            @if(isset($filters) && $filters->anyRenderable())
                <h3>Filtering</h3>
                <form method="GET">
                    {!! $filters->render() !!}
                    <div class="stack-xs">
                        <button class="btn btn-primary squished-xs" type="submit">Filter</button>
                    </div>
                </form>
            @endif

            @if($modelManager->isManualSortable())
                @if(!$managers instanceof Illuminate\Contracts\Pagination\Paginator)
                    <div class="mb-8">
                        <p class="mb-4">Deze {{ strtolower($modelManager->details()->plural) }} worden op de site weergegeven volgens een handmatige sortering.</p>
                        <button class="btn btn-primary " data-sortable-toggle>Sorteer handmatig</button>
                        <p class="font-xs mt-2" data-sortable-show-when-sorting>Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.</p>
                    </div>
                @else
                    <div class="mb-8">
                        <p class="mb-4">Deze {{ strtolower($modelManager->details()->plural) }} worden op de site weergegeven volgens een handmatige sortering.</p>
                        <a class="btn btn-primary" href="{{ $modelManager->route('sort-index') }}">Sorteer handmatig</a>
                    </div>
                @endif
            @endif

            // SIDEBAR WEG WEG ZEG IK!

            @adminCan('archive_index')
            <div class="stack-s">
                <a href="@adminRoute('archive_index')">Bekijk de gearchiveerde items</a>
            </div>
            @endAdminCan
        </div>
    </div>

@stop
