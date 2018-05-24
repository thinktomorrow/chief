@extends('chief::back._layouts.master')

@section('page-title', 'Nieuwe gebruiker uitnodigen')

@chiefheader
	@slot('title', 'Nieuwe gebruiker')
	<button data-submit-form="createForm" type="button" class="btn btn-o-primary">Stuur uitnodiging</button>
@endchiefheader

@section('content')

	<form id="createForm" action="{{ route('chief.back.users.store') }}" method="POST">
		{!! csrf_field() !!}

		@include('chief::back.users._form')

		<button type="submit" class="btn btn-primary right">Stuur uitnodiging</button>
	</form>

@endsection