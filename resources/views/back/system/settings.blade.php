@extends('chief::back._layouts.master')

@section('page-title', 'settings')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Settings')
    <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
@endcomponent

@section('content')
    <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">

        @foreach($manager->fields() as $field)
            {!! $manager->renderField($field) !!}
        @endforeach

    </form>
@stop
