@extends('back._layouts.master')

@section('title', '| Add User')

@section('content')

	<div class='col-lg-4 col-lg-offset-4'>

		<h1><i class='fa fa-user-plus'></i> Add User</h1>
		<hr>

		{{-- @include ('errors.list') --}}

		<form action="{{ route('users.store') }}" method="POST">
		{!! csrf_field() !!}

		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" value="{{ old('name') }}" class="form-control">
		</div>

		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" name="email" class="form-control" value="{{ old('email') }}">
		</div>

		<div class='form-group'>
			@foreach ($roles as $role)
				<input type="checkbox" value="{{ $role->id }}" name="roles[]">
				<label for="{{ $role->name }}">{{ ucfirst($role->name) }}</label><br>
			@endforeach
		</div>

		<div class="form-group">
			<label for="password">Password</label>
			<input name="password" type="password" value="{{ old('password') }}" class="form-control">
		</div>

		<div class="form-group">
			<label for="password">Confirm Password</label><br>
			<input type="password" class="form-control" name="password_confirmation" value="">
		</div>

			<button type="submit" value="Submit" class="btn btn-primary">Add</button>
		</form>

	</div>

@endsection