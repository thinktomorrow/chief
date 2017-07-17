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

	<div class="row">

	          <div class="mh15 pv15 br-b br-light">
	            <div class="row">
	              <div class="col-xs-7">
	        			</div>
	              <div class="col-xs-5 text-right">
	                <div class="btn-group">
	                  <button type="button" class="btn btn-default to-grid">
	                    <span class="fa fa-th"></span>
	                  </button>
	                  <button type="button" class="btn btn-default to-list">
	                    <span class="fa fa-navicon"></span>
	                  </button>
	                </div>
	              </div>
	            </div>
	          </div>



	@can('add_users')
		<!-- <a href="{{ route('users.create') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a> -->
		<div class="col-xs-6 col-sm-4 col-l-3 col-xl-3">
			<div class="panel panel-tile br-a br-grey">
				<div class="panel-heading ui-sortable-handle" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
					<span class="panel-title">New User</span>
				</div>
				<div class="panel-body text-center ">
					Test
				</div>
			</div>
		</div>
	@endcan

		@foreach (\App\User::all() as $user)
		<div class="col-xs-6 col-sm-4 col-l-3 col-xl-3">
			<div class="panel panel-tile br-a br-grey">
				<div class="panel-heading ui-sortable-handle">
					<span class="panel-title">{{ $user->shortName }}</span>
					<span class="panel-controls">
						<a href="#" class="panel-control-loader"></a>
						@can('edit_users')
							<a href="{{ route('users.edit', $user->id) }}" class="panel-control-title"></a>
						@endcan
						@can('delete_users')
							<a class="panel-control-remove" id="remove-user-toggle-{{ $user->id }}" href="#remove-user-modal-{{ $user->id }}"></a>
						@endcan
					</span>
				</div>
				<div class="panel-body text-center ">
					<img src="{{ asset('assets/img/logo.png') }}" alt="test" class="mw100">
				</div>
				<div class="panel-body br-t">
					<p><i class="fa fa-user mr10"></i> {{ $user->firstname . ' ' . $user->lastname }}</p>
					<p><i class="fa fa-envelope-o mr10"></i> {{ $user->email }}</p>
					<p><i class="fa fa-calendar mr10"></i> {{ $user->created_at->format('F d, Y H:i') }}</p>
					<p><i class="fa fa-gear mr10"></i> {{ $user->roles()->pluck('name')->implode(', ') }}</p>
				</div>
				<div class="panel-footer br-t mb20">
					<!-- <button type="button" class="btn btn-alert dark">
						RESET PASSWORD
					</button> -->
					<div class="form-group mt10">
						<div class="col-md-3 switch switch-success switch-xs" style="padding-left: 0px">
							<input id="chkEnableaccount" type="checkbox" checked="">
							<label for="chkEnableaccount"></label>
						</div>
						<label class="col-md-9">Account actief?</label>
					</div>
				</div>
				<div class="panel-footer br-t">
					<button type="button" class="btn btn-primary btn-block dark">
						RESET PASSWORD
					</button>
				</div>

			</div>
		</div>
		@endforeach

	</div>

<div class="table-responsive">

<?php /*
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
		*/ ?>

	</div>


	<!-- Panel with: Expanding Rows -->
<div class="panel" id="spy4">
		<div class="panel-heading">
			<span class="panel-title">
				<span class="fa fa-group"></span>
				Gebruikers
			</span>
		</div>
		<div class="panel-body pn">

			<table class="table footable fw-labels" data-page-size="20">
				<thead>
					<tr>
						<th>Rollen</th>
						<th>Naam</th>
						<th>Voornaam</th>
						<th>Email</th>
						<th>Aangemaakt op:</th>
						<th>Acties</th>
					</tr>
				</thead>

				<tbody>

					@foreach (\App\User::all() as $user)
						<tr>
							<td>
								<?php
								$roles = $user->roles()->pluck('name');
								foreach($roles as $item) {
									switch(strtolower($item)){
										case 'superadmin':
											echo '<span class="label label-primary">superadmin</span>';
											break;
										case 'admin':
											echo '<span class="label label-info">admin</span>';
											break;
										case 'user':
											echo '<span class="label label-warning">user</span>';
											break;
									}
								}

								?>

							</td>
							<td>{{ $user->lastname }}</td>
							<td>{{ $user->firstname }}</td>
							<td>{{ $user->email }}</td>
							<td>{{ $user->created_at->format('F d, Y H:i') }}</td>

							<td>
								@can('delete_users')
									<a class="btn btn-error pull-right" id="remove-user-toggle-{{ $user->id }}" href="#remove-user-modal-{{ $user->id }}"><i class="fa fa-trash"></i></a>
								@endcan

								@can('edit_users')
									<a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-right" style="margin-right: 3px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>
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
	</div>
</div>







</div>
