@extends('back._layouts.master')

@section('page-title','Voeg nieuw pagina toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw pagina')
    <button data-submit-form="createForm" type="button" class="btn btn-primary">Opslaan</button>
@endcomponent

@section('content')

	<form id="createForm" method="POST" action="{{ route('back.pages.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('back.pages._form')
        @include('back.pages._partials.modal')

	</form>

@stop