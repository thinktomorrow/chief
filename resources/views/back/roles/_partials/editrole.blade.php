<!-- Start: Right Sidebar -->
<aside id="sidebar_right" class="editRole-{{ $role->id }} nano affix bringtofront">

  <!-- Start: Sidebar Right Content -->
  <div class="sidebar-right-content nano-content p10">
    <h4 class="tray-title">Edit rol</h4>
      <div class="panel panel-tile card-create">
        <div class="panel-body">

          <form action="{{ route('roles.update', $role->id) }}" method="POST">
      			{!! csrf_field() !!}

          <div class="input-group mv15">
            <span class="input-group-addon text-muted">
              <i class="fa fa-key"></i>
            </span>
            <input id="focusField" type="text" name="name"  value="{{ $role->name }}"  class="form-control" placeholder="Rolnaam">
          </div>

          <h5 class="title-divider text-muted mt30 mb10">Permissies</h5>
           <?php
             $i = 1;
             $controle = false;
           ?>
           @foreach(\App\Permission::getPermissionsForIndex() as $model => $permissions)
             <div id="tree{{$role->name}}{{ $i }}" class="treeview-item">
               <ul id="treeData">
                 <li><input type="checkbox" id="chkSelectAll{{ $i }}" name="chkSelectAll{{ $i }}" onclick="checkAll('tree{{$role->name}}{{ $i }}')" class="mr5"
                   @foreach($permissions as $id => $permission)
                        @if($role->permissions->contains($id))
                          checked
                        @endif
                   @endforeach
                   >{{ $model }}
                   <ul>
                     @foreach($permissions as $id => $permission)
                       {{-- <input type="checkbox" value="{{ $permission }}" name="permissions[]" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}> --}}
                       <li id="{{ $permission }}"><input type="checkbox" value="{{ $id }}" class="mr5"
                          @if($role->permissions->contains($id))
                            checked
                          @endif
                          >{{ ucfirst($permission) }}</li><br>
                     @endforeach
                   </ul>
                 </li>
               </ul>
               <?php $i++; ?>
             </div>
           @endforeach


            <button type="submit" value="Submit" class="btn btn-warning btn-block mt35"><i class="fa fa-plus mr10" aria-hidden="true"></i>WIJZIGINGEN OPSLAAN</button>
      		</form>
        <!-- END OF THE FORM -->

      </div>
  </div>
</aside>
<!-- End: Right Sidebar -->

@push('custom-scripts')
    <script>
        function checkAll(divid) {
        if ($('#' + divid + ' input:checkbox:checked').length > 1)
        {
          $('#' + divid + ' :checkbox:enabled').prop('checked', false);
        }else{
          $('#' + divid + ' :checkbox:enabled').prop('checked', true);
        }
      }
    </script>
	@endpush
