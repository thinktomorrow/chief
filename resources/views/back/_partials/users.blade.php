<div class="col-lg-10 col-lg-offset-1">
	<h1><i class="fa fa-users"></i> User Administration

		@can('view_roles')
			<a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
		@else
			<a class="btn btn-default pull-right disabled">Roles</a>
		@endcan
		@can('view_permissions')
			<a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
		@else
			<a class="btn btn-default pull-right disabled">Permissions</a>
		@endcan
	</h1>
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
							<a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>
						@else
							<a class="btn btn-info pull-left disabled" style="margin-right: 3px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>
						@endcan

						@can('delete_users')
							<a class="btn btn-error" id="remove-user-toggle-{{ $user->id }}" href="#remove-user-modal-{{ $user->id }}"><i class="fa fa-trash"></i></a>
						@else
							<a class="btn btn-error disabled"><i class="fa fa-trash"></i></a>
						@endcan
					</td>
				</tr>
				@include('back.users._deletemodal')
				@push('custom-scripts')
				<script>
					;(function ($) {
						// Delete modal
						$("#remove-user-toggle-{{ $user->id }}").magnificPopup();
					})(jQuery);
				</script>
				@endpush
			@endforeach
			</tbody>

		</table>
	</div>

	@can('add_users')
		<a href="{{ route('users.create') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
	@else
		<a class="btn btn-success disabled"><i class="fa fa-plus" aria-hidden="true"></i></a>
	@endcan
</div>