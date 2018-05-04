@push('custom-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/vendor/plugins/footable/css/footable.core.min.css') }}">
@endpush

<div class="col-lg-12">
	<?php /*
	<!-- <h1><i class="fa fa-users"></i> User Administration

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
	<hr> -->
	<!-- @can('add_users')
		<a href="{{ route('users.create') }}" class="btn btn-success"><i class="fa fa-plus mr10" aria-hidden="true"></i>User toevoegen</a>
	@endcan -->

	<!-- Begin Grid List view buttons -->
	<!-- <div class="mh15 pv15 br-b br-light mb30">
		<div class="row">
			<div class="col-xs-7">
			</div>
			<div class="col-xs-5 text-right">
				<div class="btn-group">
					<button type="button" id="gridview" class="btn btn-default to-grid">
						<span class="fa fa-th"></span>
					</button>
					<button type="button" id="listview" class="btn btn-default to-list">
						<span class="fa fa-navicon"></span>
					</button>
				</div>
			</div>
		</div>
	</div> -->
	<!-- End Grid List view buttons -->

	<!-- Begin gridview layout -->
	<!-- <div class="row gridview hidden">
		@foreach (\Chief\Users\User::all() as $user)
		<div class="col-xs-12 col-sm-6 col-md-4 col-l-4 col-xl-3 rm-padding-top">
			<div class="panel panel-tile br-a br-grey">
				<div class="panel-heading br-n pn">
					<span class="panel-title ml5">{{ $user->shortName }}</span>
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
					<p><i class="fa fa-user mr10 gridicon"></i> {{ $user->firstname . ' ' . $user->lastname }}</p>
					<p><i class="fa fa-envelope-o mr10 gridicon"></i> {{ $user->email }}</p>
					<p><i class="fa fa-calendar mr10 gridicon"></i> {{ $user->created_at->format('F d, Y H:i') }}</p>
					<p><i class="fa fa-eye mr10 gridicon"></i> {{ isset($user->last_login) ? date('F d, Y H:i', strtotime($user->last_login)) : 'Nog niet ingelogd' }}</p>
					<p><i class="fa fa-key mr10 gridicon"></i> {{ $user->roles()->pluck('name')->implode(', ') }}</p>
				</div>

				@can('edit_users')
				<div class="panel-footer br-t mb20">
					<div class="form-group mt10">
							<?php
							$status = $user->status;
							$checkedaccount = '';
							$pendingstyle = '';
							switch(strtolower($status)){
								case 'pending':
									$label = 'Account pending';
									$pendingstyle = 'background-color: #f6bb42';
									break;
								case 'active':
									$label = 'Account actief';
									$checkedaccount = 'checked';
									break;
								default:
									$label = 'Account inactief';
									break;
							}
							?>
							<form action="{{ route('users.publish', $user->id) }}" method="post">
								{{ csrf_field() }}
								<div class="col-xs-3 col-l-2">
									<div class="switch switch-success round switch-xs" style="padding-left: 0px">
										<input id='publishAccount-{{ $user->id }}' name="publishAccount" type="checkbox" {{$checkedaccount}} onchange="this.form.submit()">
										<label for="publishAccount-{{ $user->id }}" style="{{ $pendingstyle }}"></label>
									</div>
								</div>
								<div class="col-xs-9 col-l-10">
									<label>{{ $label }}</label>
								</div>
							</form>
					</div>
				</div>
				<div class="panel-footer br-t">
					<button type="button" class="btn btn-default btn-block">
						RESET PASSWORD
					</button>
				</div>
				@endcan

			</div>
		</div>
		@endforeach
	</div> -->
<!-- End gridview layout -->
*/ ?>


<!-- Begin listview layout -->
<div class="panel listview" id="spy3">
		<div class="panel-menu">
			<input id="fooFilter" type="text" class="form-control" placeholder="Voer zoekfilter hier in">
		</div>
		<div class="panel-body pn">

			<table class="table footable"  data-filter="#fooFilter" data-page-navigation=".pagination" data-page-size="10">
				<thead>
					<tr>
						<th>Naam</th>
						<th>Voornaam</th>
						<th>Email</th>
						<th>Status</th>
						<th>Rollen</th>
						<th class="responsive-column-1">Aangemaakt op</th>
						<th class="responsive-column-2">Laatst gezien</th>
						<th>Acties</th>
					</tr>
				</thead>

				<tbody>
					@foreach (\Chief\Users\User::all() as $user)
						<tr>
							<td>{{ $user->lastname }}</td>
							<td>{{ $user->firstname }}</td>
							<td>{{ $user->email }}</td>
							<td>
								<div class="form-group">
										<?php
										$status = $user->status;
										$checkedaccount = '';
										$pendingstyle = '';
										switch(strtolower($status)){
											case 'pending':
												$label = 'Pending';
												$pendingstyle = 'background-color: #f6bb42';
												break;
											case 'active':
												$label = 'Actief';
												$checkedaccount = 'checked';
												break;
											default:
												$label = 'Inactief';
												break;
										}
										?>
										<form action="{{ route('users.publish', $user->id) }}" method="post">
											{{ csrf_field() }}
												@can('edit_users')
												<div class="switch switch-success round switch-xs" style="padding-left: 0px">
													<input id='publishAccount-{{ $user->id }}' name="publishAccount" type="checkbox" {{$checkedaccount}} onchange="this.form.submit()">
													<label for="publishAccount-{{ $user->id }}" style="{{ $pendingstyle }}"></label>
												</div>
												@endcan
												<label class="list-label">{{ $label }}</label>
										</form>
								</div>
							</td>
							<td>
								<?php
								$roles = $user->roles()->pluck('name');
								foreach($roles as $item) {
									switch(strtolower($item)){
										case 'superadmin':
											echo '<div class="badge darkblue" title="superadmin">S</div>';
											echo '<div class="hidden">superadmin</div>';
											break;
										case 'admin':
											echo '<div class="badge blue" title="admin">A</div>';
											echo '<div class="hidden">admin</div>';
											break;
										case 'user':
											echo '<div class="badge orange" title="user">U</div>';
											echo '<div class="hidden">user</div>';
											break;
									}
								}

								?>
							</td>
							<td class="responsive-column-1">{{ $user->created_at->format('F d, Y H:i') }}</td>
							<?php
								$lastlogin = $user->last_login;
								if($lastlogin){
									echo '<td class="responsive-column-2">' . $lastlogin . '</td>';
								} else{
									echo '<td class="responsive-column-2">Nog niet ingelogd</td>';
								}
							?>
							<td>
								@can('delete_users')
									<a href="#remove-user-modal-{{ $user->id }}" id="remove-user-toggle-{{ $user->id }}" class="btn btn-error btn-rounded pull-right ml5" title="delete user"><i class="fa fa-times"></i></a>
								@endcan

								@can('edit_users')
									<button id="btnEditUser" data-sidebar-id="{{$user->id}}" class="btn btn-info btn-rounded pull-right showEditUser ml5" title="edit user"><i class="fa fa-pencil" aria-hidden="true"></i></button>
								@endcan

								@can('edit_users')
									<a href="" class="btn btn-alert pull-right btn-rounded ml5" title="reset password"><i class="fa fa-refresh" aria-hidden="true"></i><span></span></a>
								@endcan
							</td>
						</tr>
						@include('back.authorization.users._deletemodal')
						@push('custom-scripts')
						<script>
							;(function ($) {
								// Delete modal
								$("#remove-user-toggle-{{ $user->id }}").magnificPopup();
							})(jQuery);
						</script>
						@endpush
						@include('back.authorization.users._partials.edituser')
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<tr>
		<td colspan="12">
			<nav class="text-center">
				<ul class="pagination hide-if-no-paging"><li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li><li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li><li class="footable-page active"><a data-page="0" href="#">1</a></li><li class="footable-page"><a data-page="1" href="#">2</a></li><li class="footable-page"><a data-page="2" href="#">3</a></li><li class="footable-page"><a data-page="3" href="#">4</a></li><li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li><li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li></ul>
			</nav>
		</td>
	</tr>
</div>
<!-- End listview layout -->
