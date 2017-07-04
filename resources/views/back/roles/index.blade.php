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

						<td>
							@foreach($role->getPermissionsForindex() as $model => $permissions)
								{{ $model }} =>
											@foreach($permissions as $permission)
												{{ $permission }},
											@endforeach
								<br>
							@endforeach
						</td>
						<td>
							@can('edit_roles')
								<a href="{{ URL::to('admin/roles/'.$role->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>
							@endcan

							@can('delete_roles')
								<a class="btn btn-error" id="remove-role-toggle-{{ $role->id }}" href="#remove-role-modal-{{ $role->id }}"><i class="fa fa-trash"></i></a>
							@endcan
						</td>
					</tr>
					@include('back.roles._deletemodal')
					@push('custom-scripts')
					<script>
						;(function ($) {
							// Delete modal
							$("#remove-role-toggle-{{ $role->id }}").magnificPopup();
						})(jQuery);
					</script>
					@endpush
				@endforeach
				</tbody>

			</table>
		</div>

		@can('add_roles')
			<a href="{{ URL::to('admin/roles/create') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
		@endcan
	</div>

@endsection