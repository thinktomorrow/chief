@extends('back._layouts.master')

@section('page-title', 'Nieuwe rol toevoegen')

@chiefheader
@slot('title', 'Nieuwe rol toevoegen')
	<div class="center-y right inline-group">
		<button data-submit-form="createForm" type="button" class="btn btn-o-primary">Voeg nieuwe rol toe</button>
	</div>
@endchiefheader

@section('content')

	<form id="createForm" action="{{ route('back.roles.store') }}" method="POST">
		{!! csrf_field() !!}

		@include('back.authorization.roles._form')

		<button type="submit" class="btn btn-primary right">Voeg nieuwe rol toe</button>
	</form>

@endsection