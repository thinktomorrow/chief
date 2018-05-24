@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw pagina toe')

@component('chief.back._layouts._partials.header')
    @slot('title', 'Nieuw pagina')
    <button data-submit-form="createForm" type="button" class="btn btn-primary">Opslaan</button>
@endcomponent

@section('content')

	<form id="createForm" method="POST" action="{{ route('chief.back.pages.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('chief::back.pages._form')
        @include('chief::back.pages._partials.modal')

	</form>

@stop