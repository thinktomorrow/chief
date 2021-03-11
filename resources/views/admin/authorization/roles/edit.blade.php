@extends('chief::back._layouts.master')

@section('page-title', $role->name)

@chiefheader
	@slot('title', $role->name)
	<div class="center-y right inline-group">
		<button data-submit-form="editForm" type="button" class="btn btn-o-primary">Bewaar</button>
	</div>
@endchiefheader

@section('content')

		<form id="editForm" action="{{ route('chief.back.roles.update', $role->id) }}" method="POST">
			{!! method_field('put') !!}
			{!! csrf_field() !!}

			@include('chief::admin.authorization.roles._form')

			<button type="submit" class="btn btn-primary right">Bewaar</button>
		</form>

@endsection
