@section('page-title')
User management
@stop

@section('topbar-right')
	@can('add_users')
		<a href="{{ route('users.create') }}" class="btn btn-success"><i class="fa fa-plus mr10" aria-hidden="true"></i>User toevoegen</a>
	@endcan
@stop

<style>
.rm-padding-top{
	padding-top: 0px !important;
}

.badge{
	height: 20px;
	width: 20px;
	font-size: 1rem;
	line-height: 14px;
	border-radius: 0px;
	margin-right: 2px;
	margin-bottom: 2px;
	cursor: help;

}

.darkblue{
	background-color: #4A89DC;
}

.blue{
	background-color: #3BAFDA;
}

.orange{
	background-color: #F6BB42;
}

.list-label{
	font-size: 1rem;
}

.panel-control-title{
	transition: 0.3s ease all;
}

.panel-control-title:hover{
	color: #3BAFDA;
	transition: 0.3s ease all;
}

.panel-control-remove{
	transition: 0.3s ease all;
}

.panel-control-remove:hover{
	color: #E9573F;
	transition: 0.3s ease all;
}

.gridicon{
	width: 20px;
	text-align: center;
}

.responsive-column-1, .repsonsive-column-2{
	display: inline-block;
}

@media screen and (max-width: 1300px) {
	.responsive-column-1{
		display: none;
	}
}
@media screen and (max-width: 1200px) {
	.responsive-column-2{
		display: none;
	}
}

</style>

@push('custom-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/vendor/plugins/footable/css/footable.core.min.css') }}">
@endpush

<div class="col-lg-12">
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

	<div class="mh15 pv15 br-b br-light mb30">
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
	</div>

	<div class="row gridview hidden">

		<div class="col-xs-12 col-sm-6 col-md-4 col-l-4 col-xl-4 rm-padding-top">
			<div class="panel panel-tile br-a br-grey card-create">
				<div class="panel-heading br-n pn">
					<span class="panel-title ml5">Maak een nieuwe gebruiker aan</span>
				</div>
				<div class="panel-body text-center ">
					<img src="{{ asset('assets/img/logo.png') }}" alt="test" class="mw100">
				</div>
				<div class="panel-body br-t">
					<!-- NAAM INPUT -->
					<div class="input-group mv15">
						<span class="input-group-addon text-muted">
              <i class="fa fa-user"></i>
            </span>
						<input type="text" name="firstname" class="form-control" placeholder="Voornaam">
						<input type="text" name="lastname" class="form-control" placeholder="Naam">
					</div>
					<!-- EMAIL INPUT -->
					<div class="input-group mv15">
						<span class="input-group-addon text-muted">
              <i class="fa fa-envelope-o"></i>
            </span>
						<input type="text" name="email" class="form-control" placeholder="E-mail">
					</div>




					<div class="section mv15 admin-form">
	          <div class="option-group field">
	            <label class="option option-primary">
	              <input type="checkbox" name="superadmin" value="checked">
	              <span class="checkbox"></span>Superadmin</label>
	            <label class="option option-primary">
	              <input type="checkbox" name="admin" value="disabled">
	              <span class="checkbox"></span>Admin</label>
	            <label class="option option-primary">
	              <input type="checkbox" name="user" value="CH">
	              <span class="checkbox"></span>User</label>
	          </div>
	          <!-- end .option-group section -->
	        </div>
				</div>

				<div class="panel-footer br-t">
					<button type="button" class="btn btn-success btn-block">
						CREATE ACCOUNT
					</button>
				</div>

			</div>
		</div>




		@foreach (\App\User::all() as $user)
		<div class="col-xs-12 col-sm-6 col-md-4 col-l-4 col-xl-4 rm-padding-top">
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

	</div>


	<!-- Panel with: Expanding Rows -->
<div class="panel listview hidden" id="spy4">
		<div class="panel-menu">
			<input id="fooFilter" type="text" class="form-control" placeholder="Enter Table Filter Criteria Here...">
		</div>
		<div class="panel-body pn">

			<table class="table footable"  data-filter="#fooFilter">
				<thead>
					<tr>
						<th>Naam</th>
						<th>Voornaam</th>
						<th>Email</th>
						<th>Status</th>
						<th>Rollen</th>
						<th class="responsive-column-1">Aangemaakt op</th>
						<th class="responsive-column-2">Laatst gezien</th>
					</tr>
				</thead>

				<tbody>

					@foreach (\App\User::all() as $user)
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
									<a href="#remove-user-modal-{{ $user->id }}" id="remove-user-toggle-{{ $user->id }}" class="btn btn-error pull-right" title="delete user"><i class="fa fa-times"></i></a>
								@endcan

								@can('edit_users')
									<a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-right" style="margin-right: 3px;" title="edit user"><i class="fa fa-pencil" aria-hidden="true"></i></a>
								@endcan

								@can('edit_users')
									<a href="" class="btn btn-alert pull-right" style="margin-right: 3px;" title="reset password"><i class="fa fa-refresh" aria-hidden="true"></i><span></span></a>
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
@push('custom-scripts')
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.all.min.js') }}"></script>
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.filter.min.js') }}"></script>
    <script>
      $(document).ready(function(){
				$('.table').footable();
        $(document.body).removeClass('sb-r-c');

				if(localStorage.getItem("UsermanagementList") == "yes") {
					$(".gridview").addClass( "hidden" );
					$(".listview").removeClass( "hidden" );
				} else {
					$(".listview").addClass( "hidden" );
					$(".gridview").removeClass( "hidden" );
				}

        $("#listview").click(function(){
					$(".gridview").addClass( "hidden" );
					$(".listview").removeClass( "hidden" );
					localStorage.setItem("UsermanagementList", "yes");
        });

        $("#gridview").click(function(){
					$(".listview").addClass( "hidden" );
					$(".gridview").removeClass( "hidden" );
					localStorage.setItem("UsermanagementList", "no");
        });
      });


    </script>


@endpush
