@push('custom-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/vendor/plugins/footable/css/footable.core.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/vendor/plugins/fancytree/skin-win8/ui.fancytree.min.css') }}">
@endpush

<div class="col-lg-12">
  <div class="panel listview" id="spy4">
  		<div class="panel-menu">
  			<input id="fooFilter" type="text" class="form-control" placeholder="Voer zoekfilter hier in">
  		</div>
  		<div class="panel-body pn">

  			<table class="table footable"  data-filter="#fooFilter"  data-page-navigation=".pagination" data-page-size="10">
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
                                  echo '<span class="blue badge" title="' . $permission . '">' . substr($permission, 0, 1) . '</span>';
                                  break;
                                case 'edit':
                                  echo '<span class="orange badge" title="' . $permission . '">' . substr($permission, 0, 1) . '</span>';
                                  break;
                                case 'add':
                                  echo '<span class="darkblue badge" title="' . $permission . '">' . substr($permission, 0, 1) . '</span>';
                                  break;
                                case 'delete':
                                  echo '<span class="red badge" title="' . $permission . '">' . substr($permission, 0, 1) . '</span>';
                                  break;
                                 default:
                                  echo '<span class="grey badge" title="' . $permission . '">' . substr($permission, 0, 1) . '</span>';
                                  break;
                              }
                            ?>
                          </div>
                        @endforeach
                  <br>
                @endforeach
              </td>
              <td>
                @can('delete_roles')
                  <a class="btn btn-error pull-right btn-rounded ml5" id="remove-role-toggle-{{ $role->id }}" href="#remove-role-modal-{{ $role->id }}"><i class="fa fa-trash"></i></a>
                @endcan
                @can('edit_roles')
                  <button id="btnEditUser" data-sidebar-id="{{ $role->id }}" class="btn btn-info btn-rounded pull-right showEditRole ml5" title="edit user"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                @endcan
              </td>
            </tr>
            @include('back.authorization.roles._deletemodal')

            @push('custom-scripts')
            <script>
              ;(function ($) {
                // Delete modal
                $("#remove-role-toggle-{{ $role->id }}").magnificPopup();
              })(jQuery);
            </script>
            @endpush
            @include('back.authorization.roles._partials.editrole')
          @endforeach
          </tbody>

    </table>
  </div>
  <tr>
		<td colspan="12">
			<nav class="text-center">
				<ul class="pagination hide-if-no-paging"><li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li><li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li><li class="footable-page active"><a data-page="0" href="#">1</a></li><li class="footable-page"><a data-page="1" href="#">2</a></li><li class="footable-page"><a data-page="2" href="#">3</a></li><li class="footable-page"><a data-page="3" href="#">4</a></li><li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li><li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li></ul>
			</nav>
		</td>
	</tr>
</div>
