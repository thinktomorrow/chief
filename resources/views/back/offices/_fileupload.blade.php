<div class="fileupload fileupload-new admin-form" data-provides="fileupload">
    <div class="fileupload-preview thumbnail mb20">
        @if ($office->hasImage())
            <img src="{!! $office->getImageUrl() !!}" alt="{{ $office->title }}">
        @else
            <em>no logo uploaded...</em>
        @endif
    </div>
    {!! $errors->first('image', '<div class="alert alert-border-left alert-sm alert-danger light alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>:message</div>') !!}
            <span class="button btn-system btn-file btn-block">
              <span class="fileupload-new">Choose a logo</span>
              <span class="fileupload-exists">Choose a new logo</span>
              <input type="file" name="featured_image">
            </span>
</div>