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
								<a href="{{ URL::to('admin/permissions/'.$permission->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>
							@endcan

							@can('delete_permissions')
									<a class="subtle subtle-danger" id="remove-permission-toggle-{{ $permission->id }}" href="#remove-permission-modal-{{ $permission->id }}"><i class="fa fa-2x fa-trash"></i></a>
							@endcan
						</td>
					</tr>
					@include('back.permissions._deletemodal')
					@push('custom-scripts')
					<script>
						;(function ($) {
							// Delete modal
							$("#remove-permission-toggle-{{ $permission->id }}").magnificPopup();
						})(jQuery);
					</script>
					@endpush
				@endforeach
				</tbody>
			</table>
		</div>

		@can('add_permissions')
			<a href="{{ URL::to('admin/permissions/create') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
		@endcan

	</div>
@endsection
