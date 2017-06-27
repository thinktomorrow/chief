<div class="col-lg-10 col-lg-offset-1">
	<h1><i class="fa fa-users"></i> User Administration

		@can('view_roles')
			<a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
		@endcan
		@can('view_permissions')
			<a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
		@endcan
	<hr>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">

			<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Date/Time Added</th>
				<th>User Roles</th>
				<th>Operations</th>
			</tr>
			</thead>

			<tbody>
			@foreach (\App\User::all() as $user)
				<tr>

					<td>{{ $user->name }}</td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->created_at->format('F d, Y H:i') }}</td>
					<td>{{ $user->roles()->pluck('name')->implode(', ') }}</td>

					<td>
						@can('edit_users')
							<a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
						@endcan

						@can('delete_users')
							<form action="{{ route('users.destroy', $user->id) }}" method="POST">
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

	@can('add_users')
		<a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
	@endcan
</div>