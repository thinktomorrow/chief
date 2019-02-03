@extends('chief::back._layouts.master')

@section('page-title', $modelManager->details()->singular .' archief')

@component('chief::back._layouts._partials.header')
    @slot('title', $modelManager->details()->singular .' archief')
    @slot('subtitle')
        <a class="center-y" href="{{ $modelManager->route('index') }}"><span class="icon icon-arrow-left"></span> Terug naar alle {{ $modelManager->details()->plural }}</a>
    @endslot
@endcomponent

@section('content')

    <div class="row gutter-s">
        @foreach($managers as $manager)
            @include('chief::back.managers.archive._rowitem')
            @include('chief::back.managers._partials.delete-modal')
        @endforeach
    </div>

@stop