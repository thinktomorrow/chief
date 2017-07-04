<!-- Admin Form Popup -->
<div id="remove-permission-modal-{{ $permission->id }}" class="popup-basic admin-form mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading text-center">
              <span class="panel-title text-danger">Delete this permission?</span>
        </div>
        <div class="panel-body text-center">
            The following permission will be permanently deleted:<br>
            <em>{{ $permission->name }}</em>.
            <br><br>Are you sure?
        </div>
        <div class="panel-footer">
            <div class="text-center">
                <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="admin-form">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger btn-lg" type="submit">YES, remove this permission</button>
                </form>
            </div>
        </div>

    </div>
    <!-- end: .panel -->
</div>
<!-- end: .admin-form -->
