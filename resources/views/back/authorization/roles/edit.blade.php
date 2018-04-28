@extends('back._layouts.master')

@section('title', '| Edit Role')

@section('content')

	<section class='col-lg-4 col-lg-offset-4'>
		<h1><i class='fa fa-key'></i> Edit Role: {{$role->name}}</h1>
		<hr>
		<form action="{{ route('back.roles.update', $role->id) }}" method="POST">
			{!! method_field('put') !!}
			{!! csrf_field() !!}
			<div class="form-group">
				<label for="name">Role Name</label>
				<input type="text" name="name" value="{{ $role->name }}" class="form-control">
			</div>



			<h5><b>Assign Permissions</b></h5>
			<div class="panel">
				{{--@foreach(\Chief\Authorization\Permission::getPermissionsForindex() as $model => $permissions)--}}
				{{--<div class="panel-heading">{{ $model }}</div>--}}
				{{--<div class="panel-body">--}}
					{{--@foreach($permissions as $permission)--}}
						{{--<input type="checkbox" value="{{ $permission }}" name="permissions[]" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>--}}
						{{--<label for="{{ $permission }}">{{ ucfirst($permission) }}</label><br>--}}
					{{--@endforeach--}}
				{{--</div>--}}
			{{--@endforeach--}}
			</div>
			<br>

			<button type="submit" value="Submit" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i> Bewaar aanpassingen</button>
		</form>
	</section>

@endsection
