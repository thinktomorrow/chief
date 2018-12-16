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

    @if($managers->isEmpty() && $modelManager->can('create'))
        <div class="center-center stack-xl">
            <a href="{{ $modelManager->route('create') }}" class="btn btn-primary squished-l">
                <i class="icon icon-zap icon-fw"></i> Tijd om een {{ $modelManager->details()->singular }} toe te voegen
            </a>
        </div>
    @endif

    <div class="row gutter-s">
        @foreach($managers as $manager)
            @include('chief::back.managers._partials._rowitem')
            @include('chief::back.managers._partials.delete-modal')
        @endforeach
    </div>
    @if($modelManager->assistedBy('archive'))

        <?php $archiveAssistant = $modelManager->assistant('archive'); ?>

        @if( ! $archiveAssistant->findAll()->isEmpty())
            <hr>
            <div class="center-x">
                <a class="squished-s" href="{{ $archiveAssistant->route('index') }}">Bekijk de gearchiveerde items</a>
            </div>
        @endif
    @endif

@stop