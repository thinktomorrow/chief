@extends('chief::back._layouts.master')

@section('page-title', $modelManager->managerDetails()->plural)

@component('chief::back._layouts._partials.header')
    @slot('title', $modelManager->managerDetails()->plural)
    <div class="inline-group-s">
        <a href="{{ $modelManager->route('create') }}" class="btn btn-primary">
            <i class="icon icon-plus"></i>
            Voeg een {{ $modelManager->managerDetails()->singular }} toe
        </a>
    </div>
@endcomponent

@section('content')

    @if($managers->isEmpty())
        <div class="center-center stack-xl">
            <a href="{{ $modelManager->route('create') }}" class="btn btn-primary squished-l">
                <i class="icon icon-zap icon-fw"></i> Tijd om een {{ $modelManager->managerDetails()->singular }} toe te voegen
            </a>
        </div>
    @endif

    @foreach($managers as $manager)
        @include('chief::back.managers._partials._rowitem')
    @endforeach
@stop