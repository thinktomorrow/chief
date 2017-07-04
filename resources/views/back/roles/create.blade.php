@extends('back._layouts.master')

@section('title', '| Add Role')

@section('content')

	<div class='col-lg-4 col-lg-offset-4'>

		<h1><i class='fa fa-key'></i> Add Role</h1>
		<hr>
		{{-- @include ('errors.list') --}}

		<form action="{{ route('roles.store') }}" method="POST">
			{!! csrf_field() !!}

		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" value="{{ old('name') }}" class="form-control">
		</div>

		<h5><b>Assign Permissions</b></h5>

		<div class='form-group'>
			@foreach ($permissions as $permission)
				<input type="checkbox" value="{{ $permission->id }}" name="permissions[]">
				<label for="{{ $permission->name }}">{{ ucfirst($permission->name) }}</label><br>
			@endforeach
		</div>

			<button type="submit" value="Submit" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
		</form>

	</div>

@endsection