@extends('back._layouts.master')

@section('page-title', $user->fullname)

@chiefheader
	@slot('title', $user->fullname)
	<button data-submit-form="updateForm" type="button" class="btn btn-o-primary">Bewaar</button>
@endchiefheader

@section('content')

	<form id="updateForm" action="{{ route('back.users.update',$user->id) }}" method="POST">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="PUT">

		@include('back.users._form')

		<button type="submit" class="btn btn-primary right">Bewaar aanpassingen</button>
	</form>

@endsection