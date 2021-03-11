@extends('chief::back._layouts.master')

@section('page-title','Audit')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Audit.')
@endcomponent

@section('content')
    @include('chief::admin.audit.rows')
@stop
