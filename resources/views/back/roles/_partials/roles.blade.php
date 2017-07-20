@push('custom-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/vendor/plugins/footable/css/footable.core.min.css') }}">
@endpush

<div class="col-lg-12">
  <div class="panel listview" id="spy4">
  		<div class="panel-menu">
  			<input id="fooFilter" type="text" class="form-control" placeholder="Voer zoekfilter hier in">
  		</div>
  		<div class="panel-body pn">

  			<table class="table footable"  data-filter="#fooFilter">
  				<thead>
            <tr>
              <th>Rol</th>
              <th>Rechten</th>
              <th>Acties</th>
            </tr>
          </thead>

          <tbody>
          @foreach ($roles as $role)
            <tr>

              <td>{{ $role->name }}</td>

              <td>
                @foreach($role->getPermissionsForindex() as $model => $permissions)
                  <div class="rolename">{{ $model }}</div>
                        @foreach($permissions as $permission)
                          <div class="rolepermission">
                            <?php
                              switch ($permission){
                                case 'view':
                                  echo '<span class="blue">' . $permission . '</span>';
                                  break;
                                case 'edit':
                                  echo '<span class="orange">' . $permission . '</span>';
                                  break;
                                case 'add':
                                  echo '<span class="darkblue">' . $permission . '</span>';
                                  break;
                                case 'delete':
                                  echo '<span class="red">' . $permission . '</span>';
                                  break;
                              }
                            ?>
                          </div>
                        @endforeach
                  <br>
                @endforeach
              </td>
              <td>
                @can('edit_roles')
                  <a href="{{ URL::to('admin/roles/'.$role->id.'/edit') }}" class="btn btn-info pull-right" style="margin-right: 3px;"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                @endcan

                @can('delete_roles')
                  <a class="btn btn-error pull-right" id="remove-role-toggle-{{ $role->id }}" href="#remove-role-modal-{{ $role->id }}"><i class="fa fa-trash"></i></a>
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
</div>
