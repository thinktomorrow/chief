@extends('chief::back._layouts.master')

@section('page-title', $modelManager->managerDetails()->plural)

@component('chief::back._layouts._partials.header')
    @slot('title', $modelManager->managerDetails()->plural)
    <div class="inline-group-s">
        @if($modelManager->can('create'))
            <a href="{{ $modelManager->route('create') }}" class="btn btn-primary">
                <i class="icon icon-plus"></i>
                Voeg een {{ $modelManager->managerDetails()->singular }} toe
            </a>
        @endif
    </div>
@endcomponent

@section('content')

    @if($managers->isEmpty() && $modelManager->can('create'))
        <div class="center-center stack-xl">
            <a href="{{ $modelManager->route('create') }}" class="btn btn-primary squished-l">
                <i class="icon icon-zap icon-fw"></i> Tijd om een {{ $modelManager->managerDetails()->singular }} toe te voegen
            </a>
        </div>
    @endif

    <div v-cloak class="v-loader inset-xl text-center">loading...</div>
    <div v-cloak>
        @foreach($managers as $manager)
            @include('chief::back.managers._partials._rowitem')
            @include('chief::back.managers._partials.delete-modal')
        @endforeach
    </div>

@stop