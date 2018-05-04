@extends('back._layouts.master')

@section('title', '| Create Permission')

@section('content')

	<div class='col-lg-4 col-lg-offset-4'>

		{{-- @include ('errors.list') --}}

		<h1><i class='fa fa-key'></i> Add Permission</h1>
		<br>

		<form action="{{ route('permissions.store') }}" method="POST">
			{!! csrf_field() !!}

			<div class="form-group">
				<label for="name">Permission Name</label>
				<input type="text" name="name" value="{{ old('name') }}" class="form-control">
			</div>
			<br>

			@if(!$roles->isEmpty())

				<h4>Assign Permission to Roles</h4>

				@foreach ($roles as $role)
					<input type="checkbox" value="{{ $role->id }}" name="roles[]">
					<label for="{{ $role->name }}">{{ ucfirst($role->name) }}</label><br>
				@endforeach

			@endif

			<br>
			<button type="submit" value="Submit" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
		</form>

	</div>

@endsection