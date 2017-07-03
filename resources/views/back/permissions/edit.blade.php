@extends('back._layouts.master')

@section('title', '| Edit Permission')

@section('content')

	<div class='col-lg-4 col-lg-offset-4'>

		{{-- @include ('errors.list') --}}

		<h1><i class='fa fa-key'></i> Edit {{$permission->name}}</h1>
		<br>
		<form action="{{ route('permissions.update', $permission->id) }}" method="POST">
			{!! method_field('put') !!}
			{!! csrf_field() !!}
			<div class="form-group">
				<label for="name">Permission Name</label>
				<input type="text" name="name" value="{{ $permission->name }}" class="form-control">
			</div>
			<br>

			@can('edit_permissions')
				<button type="submit" value="Submit" class="btn btn-primary">Update</button>
			@endcan
		</form>

	</div>

@endsection