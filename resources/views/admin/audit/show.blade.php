@extends('chief::back._layouts.master')

@section('page-title','Audit')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Audit for '. $causer->fullname . '')
    @slot('subtitle')
    <a class="center-y" href="{{ route('chief.back.audit.index') }}">
        <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
        {{-- Terug naar het menu overzicht --}}
    </a>
@endslot
@endcomponent

@section('content')
    @include('chief::admin.audit.rows')
@stop
