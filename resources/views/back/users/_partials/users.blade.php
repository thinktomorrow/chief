@section('page-title')
Users
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



</style>

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
	@can('add_users')
		<a href="{{ route('users.create') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
	@endcan

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

	<div class="row gridview">


		@foreach (\App\User::all() as $user)
		<div class="col-xs-12 col-sm-6 col-md-4 col-l-4 col-xl-3 rm-padding-top">
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
					<p><i class="fa fa-key mr10"></i> {{ $user->roles()->pluck('name')->implode(', ') }}</p>
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
		<div class="panel-body pn">

			<table class="table footable" data-page-size="20">
				<thead>
					<tr>
						<th>Naam</th>
						<th>Voornaam</th>
						<th>Email</th>
						<th>Status</th>
						<th>Rollen</th>
						<th>Aangemaakt op</th>
						<th>Laatst gezien</th>
					</tr>
				</thead>

				<tbody>

					@foreach (\App\User::all() as $user)
						<tr>
							<td>{{ $user->lastname }}</td>
							<td>{{ $user->firstname }}</td>
							<td>{{ $user->email }}</td>
							@can('edit_users')
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
												<div class="switch switch-success round switch-xs" style="padding-left: 0px">
													<input id='publishAccount-{{ $user->id }}' name="publishAccount" type="checkbox" {{$checkedaccount}} onchange="this.form.submit()">
													<label for="publishAccount-{{ $user->id }}" style="{{ $pendingstyle }}"></label>
												</div>
												<label>{{ $label }}</label>
										</form>
								</div>
							</td>
							@endcan
							<td>
								<?php
								$roles = $user->roles()->pluck('name');
								foreach($roles as $item) {
									switch(strtolower($item)){
										case 'superadmin':
											echo '<div class="badge darkblue">S</div>';
											break;
										case 'admin':
											echo '<div class="badge blue">A</div>';
											break;
										case 'user':
											echo '<div class="badge orange">U</div>';
											break;
									}
								}

								?>
							</td>
							<td>{{ $user->created_at->format('F d, Y H:i') }}</td>
							<?php
								$lastlogin = $user->last_login;
								if($lastlogin){
									echo '<td>' . $lastlogin . '</td>';
								} else{
									echo '<td>Nog niet ingelogd</td>';
								}
							?>
							<td>
								@can('delete_users')
									<a href="#remove-user-modal-{{ $user->id }}" id="remove-user-toggle-{{ $user->id }}" class="btn btn-default pull-right"><i class="fa fa-trash"></i></a>
								@endcan

								@can('edit_users')
									<a href="{{ route('users.edit', $user->id) }}" class="btn btn-default pull-right" style="margin-right: 3px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>
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
    <script>
      $(document).ready(function(){

        $(document.body).removeClass('sb-r-c');

        $("#listview").click(function(){
					$(".listview").removeClass( "hidden" );
					$(".gridview").addClass( "hidden" );
        });

        $("#gridview").click(function(){
					$(".listview").addClass( "hidden" );
					$(".gridview").removeClass( "hidden" );
        });
      });
    </script>


@endpush
