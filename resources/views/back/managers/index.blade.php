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
                            <p>Geen resultaten gevonden voor uw huidige filtering.
                            <br><a href="{{ $modelManager->route('index') }}">Bekijk alle resultaten</a>
                            </p>
                        </div>

                    @else

                        @foreach($managers as $manager)
                            @include('chief::back.managers._partials._rowitem')
                            @include('chief::back.managers._partials.delete-modal')
                            @if($manager->isAssistedBy('archive'))
                                @include('chief::back.managers._partials.archive-modal')
                            @endif
                        @endforeach

                    @endif
                
                @endif

            </div>

        </div>

        <div class="column-3">

            @if( $modelManager::filters()->any() )
                <div class="mb-8">
                    <h3>Filtering</h3>
                    <form class="stack-s" method="GET">
                        {!! $modelManager::filters()->render() !!}

                        <div class="stack-xs">
                            <button class="btn btn-primary squished-xs" type="submit">Filter</button>
                        </div>
                    </form>
                </div>
            @endif

            {!! $modelManager::sections()->sidebar !!}

            @if($modelManager->isAssistedBy('archive') && $archiveAssistant = $modelManager->assistant('archive'))
                @if( ! $archiveAssistant->findAll()->isEmpty())
                    <div class="stack-s">
                        <a href="{{ $archiveAssistant->route('index') }}">Bekijk de gearchiveerde items</a>
                    </div>
                @endif
            @endif

        </div>
    </div>

@stop
