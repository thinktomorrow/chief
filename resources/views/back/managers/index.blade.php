@extends('chief::back._layouts.master')

@section('page-title', $modelManager->details()->plural)

@component('chief::back._layouts._partials.header')
    @slot('title', $modelManager->details()->plural)
    <div class="inline-group-s">
        @if($modelManager->can('create'))
            <a href="{{ $modelManager->route('create') }}" class="btn btn-primary">
                <i class="icon icon-plus"></i>
                Voeg een {{ $modelManager->details()->singular }} toe
            </a>
        @endif
    </div>
@endcomponent

@section('content')

    @if($managers->isEmpty() && !$modelManager->filters()->anyApplied())
        @if($modelManager->can('create'))
            <div class="center-center stack-xl">
                <a href="{{ $modelManager->route('create') }}" class="btn btn-primary squished-l">
                    <i class="icon icon-zap icon-fw"></i> Tijd om een {{ $modelManager->details()->singular }} toe te voegen
                </a>
            </div>
        @else
            <p>Nog geen {{ $modelManager->details()->singular }} toegevoegd. Je hebt echter niet de nodige rechten om er een toe te voegen.</p>
        @endif
    @else
        <div class="row gutter stack">
            <div class="column-9">
                <div class="row gutter-s">
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
                </div>
            </div>
            <div class="column-3">

                @if( $modelManager::filters()->any() )
                    <h3>Filtering</h3>
                    <form class="stack" method="GET">
                        {!! $modelManager::filters()->render() !!}

                        <div class="stack-xs">
                            <button class="btn btn-primary squished-xs" type="submit">Filter</button>
                        </div>
                    </form>
                @endif
                {!! $modelManager::sections()->sidebar !!}

                @if($modelManager->isAssistedBy('archive') && $archiveAssistant = $modelManager->assistant('archive'))
                    @if( ! $archiveAssistant->findAll()->isEmpty())
                        <h3>Gearchiveerde items</h3>
                        <div class="stack-xs">
                            <a href="{{ $archiveAssistant->route('index') }}">Bekijk de gearchiveerde items</a>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    @endif



@stop
