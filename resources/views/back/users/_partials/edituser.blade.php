<aside id="sidebar_right" class="editUser-{{ $user->id }} nano affix bringtofront">

	{{-- @include ('errors.list') --}}

	<!-- Start: Sidebar Right Content -->
  <div class="sidebar-right-content nano-content p10">
    <h4 class="tray-title">Edit user</h4>
      <div class="panel panel-tile card-create">
        <div class="panel-body text-center ">
          <img src="{{ asset('assets/img/logo.png') }}" alt="test" class="mw100">
        </div>
        <div class="panel-body">

          <!-- STARTING THE FORM -->
          <form action="{{ route('users.update', $user->id) }}" class="formEditUser" method="POST">
          {!! csrf_field() !!}
            <input type="hidden" name="_method" value="PUT">
            <!-- NAAM INPUT -->
            <div class="input-group mv15">
              <span class="input-group-addon text-muted">
                <i class="fa fa-user"></i>
              </span>
              <input id="focusField" type="text" name="firstname" value="{{ $user->firstname }}" class="form-control" placeholder="Voornaam">
              <input type="text" name="lastname" value="{{ $user->lastname }}" class="form-control" placeholder="Naam">
            </div>
            <!-- EMAIL INPUT -->
            <div class="input-group mv15">
              <span class="input-group-addon text-muted">
                <i class="fa fa-envelope-o"></i>
              </span>
              <input type="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="E-mail">
            </div>

            <!-- ROLE INPUT -->
            <div class="section mv15 admin-form">
              <div class="option-group">
							  @foreach (\Chief\Authorization\Role::all() as $role)
                  <p><label class="block mt15 option option-primary">
                  <input type="checkbox" value="{{ $role->id }}" name="roles[]" {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                  <span class="checkbox"></span>{{ $role->name }}</label></p>
								@endforeach

              </div>
            </div>
          </div>

          <div class="panel-footer">
            <button type="submit" value="Submit" class="btn btn-warning btn-block"><i class="fa fa-plus mr10" aria-hidden="true"></i>WIJZIGINGEN OPSLAAN</button>
          </div>
        </form>
        <!-- END OF THE FORM -->

      </div>
			<!-- End: Sidebar Right Content -->
			</aside>
			<!-- End: Right Sidebar -->
	</div>
