@extends('back._layouts.master')

@section('title', '| Edit Role')

@section('content')

	<div class='col-lg-4 col-lg-offset-4'>
		<h1><i class='fa fa-key'></i> Edit Role: {{$role->name}}</h1>
		<hr>
		{{-- @include ('errors.list')
	 --}}
		<form action="{{ route('roles.update', $role->id) }}" method="POST">
			{!! method_field('put') !!}
			{!! csrf_field() !!}
			<div class="form-group">
				<label for="name">Role Name</label>
				<input type="text" name="name" value="{{ $role->name }}" class="form-control">
			</div>

			<h5><b>Assign Permissions</b></h5>
			@foreach ($permissions as $permission)
				<input type="checkbox" value="{{ $permission->id }}" name="permissions[]" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
				<label for="{{ $permission->name }}">{{ ucfirst($permission->name) }}</label><br>
			@endforeach
			<br>

			@can('edit_roles')
				<button type="submit" value="Submit" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></button>
			@endcan
		</form>
	</div>

@endsection