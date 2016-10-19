<div class="bs-component text-center">
    @if ($article->hasThumb())
        <div id="thumb-current">
            <img class="thumbnail" src="{!! $article->getThumbUrl() !!}" alt="{{ $article->title }}">
        </div>
    @endif
    <div class="thumb-preview" id="thumb-preview"></div>
    <div>
        <a class="btn btn-sm btn-file btn-success">
            <i class="fa fa-upload" aria-hidden="true"></i>
            Upload nieuwe thumbnail
            <input type="file" id="thumb-upload" name="thumb" accept="image/*">
        </a>
        <i class="fa fa-question-circle" data-toggle="tooltip" title="Een thumbnail moet een vierkant zijn. Minimum 200 x 200 pixels groot."></i>
    </div>

    <input type="hidden" name="thumb_datauri" id="thumb-datauri" value="{{ old('thumb_datauri') }}">
    <input type="hidden" name="originalthumb_datauri" id="originalthumb-datauri" value="{{ old('originalthumb_datauri') }}">
    <input type="hidden" name="crop_topLeftX" id="crop_topLeftX" value="{{ old('crop_topLeftX') }}">
    <input type="hidden" name="crop_topLeftY" id="crop_topLeftY" value="{{ old('crop_topLeftY') }}">
    <input type="hidden" name="crop_bottomRightX" id="crop_bottomRightX" value="{{ old('crop_bottomRightX') }}">
    <input type="hidden" name="crop_bottomRightY" id="crop_bottomRightY" value="{{ old('crop_bottomRightY') }}">
    <input type="hidden" name="crop_zoom" id="crop_zoom" value="{{ old('crop_zoom') }}">

</div>