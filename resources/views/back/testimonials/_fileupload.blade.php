<div class="fileupload fileupload-new admin-form" data-provides="fileupload">
    <div class="fileupload-preview thumbnail mb20">
        @if ($testimonial->hasImage())
            <img src="{!! $testimonial->getImageUrl() !!}" alt="{{ $testimonial->name }}">
        @else
            <em>no featured image uploaded...</em>
        @endif
    </div>
    {!! $errors->first('image', '<div class="alert alert-border-left alert-sm alert-danger light alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>:message</div>') !!}
            <span class="button btn-system btn-file btn-block">
              <span class="fileupload-new">Choose an image</span>
              <span class="fileupload-exists">Choose a new image</span>
              <input type="file" name="featured_image">
            </span>
</div>