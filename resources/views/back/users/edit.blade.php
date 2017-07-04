@extends('back._layouts.master')

@section('title', '| Edit User')

@section('content')

	<div class='col-lg-4 col-lg-offset-4'>

		<h1><i class='fa fa-user-plus'></i> Edit {{$user->name}}</h1>
		<hr>
		{{-- @include ('errors.list') --}}

		<form action="{{ route('users.update', $user->id) }}" method="POST">
			{!! method_field('put') !!}
			{!! csrf_field() !!}
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" name="name" value="{{ $user->name }}" class="form-control">
			</div>

			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" name="email" class="form-control" value="{{ $user->email }}">
			</div>

			<h5><b>Give Role</b></h5>

			<div class='form-group'>
				@foreach ($roles as $role)
					<input type="checkbox" value="{{ $role->id }}" name="roles[]" {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
					<label for="{{ $role->name }}">{{ ucfirst($role->name) }}</label><br>
				@endforeach
			</div>

			{{--<div class="form-group">--}}
				{{--<label for="password">Password</label>--}}
				{{--<input name="password" type="password" value="" class="form-control">--}}

			{{--</div>--}}

			{{--<div class="form-group">--}}
				{{--<label for="password">Confirm Password</label><br>--}}
				{{--<input type="password" class="form-control" name="password_confirmation" value="">--}}

			{{--</div>--}}

			@can('edit_users')
				<button type="submit" value="Submit" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></button>
			@endcan
		</form>

	</div>

@endsection