@extends('chief::layout.master')

@section('page-title', $modelManager->details()->singular .' archief')

@component('chief::layout._partials.header')
    @slot('title', $modelManager->details()->singular .' archief')
    @slot('subtitle')
        <a class="center-y" href="{{ $modelManager->route('index') }}">
            <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
            {{-- Terug naar alle {{ $modelManager->details()->plural }} --}}
        </a>
    @endslot
@endcomponent

@section('content')
    <div class="stack">
        <div class="row gutter-l stack">
            @foreach($managers as $manager)
                @include('chief::back.managers.archive._rowitem')
            @endforeach
        </div>
    </div>
@stop
