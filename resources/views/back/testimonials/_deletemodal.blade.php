<!-- Admin Form Popup -->
<div id="remove-testimonial-modal" class="popup-basic admin-form mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading text-center">
              <span class="panel-title text-danger">Remove this testimonial?</span>
        </div>
        <div class="panel-body text-center">
            This will permanently remove the testimonial from:<br>
            <em>{{ $testimonial->name }} - {{ $testimonial->company }}</em>.
            <br><br>Are you sure?
        </div>
        <div class="panel-footer">
            <div class="text-center">
                <form action="{{ route('admin.testimonials.destroy',$testimonial->id) }}" method="POST" class="admin-form">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger btn-lg" type="submit">Yes, delete testimonial</button>
                </form>
            </div>
        </div>

    </div>
    <!-- end: .panel -->
</div>
<!-- end: .admin-form -->
