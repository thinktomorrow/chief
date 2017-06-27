@extends('back._layouts.master')

@section('title', '| Permissions')

@section('content')

	<div class="col-lg-10 col-lg-offset-1">
		<h1><i class="fa fa-key"></i>Available Permissions

			@can('view_users')
				<a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
			@endcan
			@can('view_roles')
				<a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a></h1>
			@endcan
		<hr>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">

				<thead>
				<tr>
					<th>Permissions</th>
					<th>Operation</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($permissions as $permission)
					<tr>
						<td>{{ $permission->name }}</td>
						<td>
							@can('edit_permissions')
								<a href="{{ URL::to('admin/permissions/'.$permission->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
							@endcan

							@can('delete_permissions')
								<form action="{{ route('permissions.destroy', $permission->id) }}" method="POST">
									<input name="_method" type="hidden" value="DELETE">
									{!! csrf_field() !!}
									<button type="submit" value="Submit" class="btn btn-danger">Delete</button>
								</form>
							@endcan
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>

		@can('add_permissions')
			<a href="{{ URL::to('admin/permissions/create') }}" class="btn btn-success">Add Permission</a>
		@endcan

	</div>

@endsection