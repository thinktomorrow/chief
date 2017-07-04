<!-- Admin Form Popup -->
<div id="remove-role-modal-{{ $role->id }}" class="popup-basic admin-form mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading text-center">
              <span class="panel-title text-danger">Delete this role?</span>
        </div>
        <div class="panel-body text-center">
            The following permission will be permanently deleted:<br>
            <em>{{ $role->name }}</em>.
            <br><br>Are you sure?
        </div>
        <div class="panel-footer">
            <div class="text-center">
                <form action="{{ route('roles.destroy',$role->id) }}" method="POST" class="admin-form">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger btn-lg" type="submit">YES, remove this role</button>
                </form>
            </div>
        </div>

    </div>
    <!-- end: .panel -->
</div>
<!-- end: .admin-form -->
