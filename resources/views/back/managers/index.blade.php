
@extends('chief::back._layouts.master')

@section('page-title', $modelManager->details()->plural)

@component('chief::back._layouts._partials.header')
    @slot('title', $modelManager->details()->plural)
    <div class="inline-group-s">
        @if($modelManager->can('create'))
            <a href="{{ $modelManager->route('create') }}" class="btn btn-secondary inline-flex items-center">
                <span class="mr-2"><svg width="18" height="18"><use xlink:href="#add"/></svg></span>
                <span>Voeg een {{ $modelManager->details()->singular }} toe</span>
            </a>
        @endif
    </div>
@endcomponent

@section('content')

    <div class="row gutter-l stack">
        <div class="column-9">
            <div class="row gutter-s">

                @if($managers->isEmpty() && !$modelManager->filters()->anyApplied())

                    @if($modelManager->can('create'))
                        <div class="stack">
                            <a href="{{ $modelManager->route('create') }}" class="btn btn-primary inline-flex items-center">
                                <span class="mr-2"><svg width="18" height="18"><use xlink:href="#zap"/></svg></span>
                                <span>Tijd om een {{ $modelManager->details()->singular }} toe te voegen</span>
                            </a>
                        </div>
                    @else
                        <p>Nog geen {{ $modelManager->details()->singular }} toegevoegd. Je hebt echter niet de nodige rechten om er een toe te voegen.</p>
                    @endif

                @else

                    @if($managers->isEmpty())

                        <div class="stack">
                            <p>Geen resultaten gevonden voor de huidige filtering.
                            <br><a href="{{ $modelManager->route('index') }}">Bekijk alle resultaten</a>
                            </p>
                        </div>

                    @else

                    <div
                        data-sortable-type="{{ get_class($modelManager->modelInstance()) }}"
                        id="{{ $modelManager->isManualSortable() ? 'js-sortable' : '' }}">
                        @include($modelManager->indexView(), array_merge([
                            'managers' => $managers,
                        ], $modelManager->indexViewData()))
                    </div>

                    @endif

                @endif

            </div>
            @if($managers instanceof Illuminate\Contracts\Pagination\Paginator)
                {{ $managers->links('chief::back.managers.pagination') }}
            @endif

        </div>

        <div class="column-3">

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

            @if( $modelManager::filters()->any() )
                <div class="mb-8">
                    <h3>Filtering</h3>
                    <form method="GET">
                        {!! $modelManager::filters()->render() !!}
                        <div class="stack-xs">
                            <button class="btn btn-primary squished-xs" type="submit">Filter</button>
                        </div>
                    </form>
                </div>
            @endif

            {!! $modelManager::sections()->sidebar !!}

            @if($modelManager->isAssistedBy('archive') && $archiveAssistant = $modelManager->assistant('archive'))
                @if( ! $archiveAssistant->findAllArchived()->isEmpty())
                    <div class="stack-s">
                        <a href="{{ $archiveAssistant->route('index') }}">Bekijk de gearchiveerde items</a>
                    </div>
                @endif
            @endif

        </div>
    </div>

@stop
