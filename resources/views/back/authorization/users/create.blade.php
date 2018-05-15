@extends('back._layouts.master')

@section('page-title', 'Nieuwe gebruiker uitnodigen')

@chiefheader
	@slot('title', 'Nieuwe rol toevoegen')
	<div class="center-y right inline-group">
		<button data-submit-form="createForm" type="button" class="btn btn-o-primary">Stuur uitnodiging</button>
	</div>
@endchiefheader

@section('content')

	<form id="createForm" action="{{ route('back.users.store') }}" method="POST">
		{!! csrf_field() !!}

		@chiefformgroup(['field' => 'email'])
		@slot('label', 'E-mail')
			@slot('description', 'Dit e-mail adres geldt als login. Hierop ontvangt de nieuwe gebruiker een uitnodiging.')
			<input class="input inset-s" type="email" name="email" value="{{ old('email','') }}">
		@endchiefformgroup

		<button type="submit" class="btn btn-primary right">Stuur uitnodiging</button>
	</form>

@endsection