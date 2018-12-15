@extends('chief::back._layouts.master')

@section('page-title', $modelManager->modelDetails()->plural)

@component('chief::back._layouts._partials.header')
    @slot('title', $modelManager->modelDetails()->plural)
    <div class="inline-group-s">
        @if($modelManager->can('create'))
            <a href="{{ $modelManager->route('create') }}" class="btn btn-primary">
                <i class="icon icon-plus"></i>
                Voeg een {{ $modelManager->modelDetails()->singular }} toe
            </a>
        @endif
    </div>
@endcomponent

@section('content')

    @if($managers->isEmpty() && $modelManager->can('create'))
        <div class="center-center stack-xl">
            <a href="{{ $modelManager->route('create') }}" class="btn btn-primary squished-l">
                <i class="icon icon-zap icon-fw"></i> Tijd om een {{ $modelManager->modelDetails()->singular }} toe te voegen
            </a>
        </div>
    @endif

    <div class="row gutter-s">
        @foreach($managers as $manager)
            @include('chief::back.managers._partials._rowitem')
            @include('chief::back.managers._partials.delete-modal')
        @endforeach
    </div>

@stop