<!-- Start: Right Sidebar -->
  <aside id="sidebar_right" class="nano affix bringtofront">

    <!-- Start: Sidebar Right Content -->
    <div class="sidebar-right-content nano-content p10">
      <h4 class="tray-title">Nieuwe gebruiker</h4>
        <div class="panel panel-tile card-create">
          <div class="panel-body text-center ">
            <img src="{{ asset('assets/img/logo.png') }}" alt="test" class="mw100">
          </div>
          <div class="panel-body">

            <!-- STARTING THE FORM -->
            <form action="{{ route('users.store') }}" method="POST">
            {!! csrf_field() !!}
              <!-- NAAM INPUT -->
              <div class="input-group mv15">
                <span class="input-group-addon text-muted">
                  <i class="fa fa-user"></i>
                </span>
                <input id="focusField" type="text" name="firstname" value="{{ old('firstname') }}" class="form-control" placeholder="Voornaam">
                <input type="text" name="lastname" value="{{ old('lastname') }}" class="form-control" placeholder="Naam">
              </div>
              <!-- EMAIL INPUT -->
              <div class="input-group mv15">
                <span class="input-group-addon text-muted">
                  <i class="fa fa-envelope-o"></i>
                </span>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="E-mail">
              </div>

              <!-- ROLE INPUT -->
              <div class="section mv15 admin-form">
                <div class="option-group">
                  @foreach (\App\Role::all() as $role)
                    <p><label class="block mt15 option option-primary">
                    <input type="checkbox" name="{{ $role->name }}" value="{{ $role->id }}">
                    <span class="checkbox"></span>{{ $role->name }}</label></p>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="panel-footer">
              <button type="submit" value="Submit" class="btn btn-success btn-block"><i class="fa fa-plus mr10" aria-hidden="true"></i>TOEVOEGEN</button>
            </div>
          </form>
          <!-- END OF THE FORM -->

        </div>
    </div>
    <!-- End: Sidebar Right Content -->
  </aside>
  <!-- End: Right Sidebar -->
