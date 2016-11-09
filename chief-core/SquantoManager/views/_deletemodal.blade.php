<!-- Admin Form Popup -->
<div id="remove-line-modal" class="popup-basic admin-form mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading text-center">
              <span class="panel-title text-danger">Remove this translation key?</span>
        </div>
        <div class="panel-body text-center">
            This will permanently remove translation key:
            <em>{{ $line->key }}</em> and all of its translations.
            <br><br>Are you sure?
        </div>
        <div class="panel-footer">
            <div class="text-center">
                <form action="{{ route('back.squanto.lines.destroy',$line->id) }}" method="POST" class="admin-form">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger btn-lg" type="submit">Yes, delete the translation key</button>
                </form>
            </div>
        </div>

    </div>
    <!-- end: .panel -->
</div>
<!-- end: .admin-form -->
