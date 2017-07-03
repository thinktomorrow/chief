@extends('back._layouts.master')

@section('title', '| Roles')

@section('content')

	<div class="col-lg-10 col-lg-offset-1">
		<h1><i class="fa fa-key"></i> Roles

			@can('view_users')
				<a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
			@endcan
			@can('view_permissions')
				<a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
			@endcan
		<hr>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Role</th>
					<th>Permissions</th>
					<th>Operation</th>
				</tr>
				</thead>

				<tbody>
				@foreach ($roles as $role)
					<tr>

						<td>{{ $role->name }}</td>

						<td>{{  $role->permissions()->pluck('name')->implode(', ') }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
						<td>
							@can('edit_roles')
								<a href="{{ URL::to('admin/roles/'.$role->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
							@endcan

							@can('delete_roles')
								<form action="{{ route('roles.destroy', $role->id) }}" method="POST">
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

		@can('add_roles')
			<a href="{{ URL::to('admin/roles/create') }}" class="btn btn-success">Add Role</a>
		@endcan
	</div>

@endsection