<style>
.checkbox-delete{
  display: none;
}
.media-gallery .media{
  transition: all 0.15s ease-in-out;
}
.media-gallery .media:hover{
  box-shadow: 5px 5px 15px 0px #d8d8d8;
  cursor: pointer;
}
.media-gallery .media:hover .checkbox-delete{
  display: block!important;
}
.media.selected{
  border: 5px solid #4a89dc;
}
.media.selected h2{
  color: #4a89dc;
}
</style>
<section class="media-gallery mh15 pv15">
  <div id="mix-container">
    <div class="row">
      @foreach($library as $media)
        <!-- image thumb -->
        <div class="mix col-md-3">
          <div class="panel media pbn">
            <div class="panel-heading">
              <span class="panel-icon">
                <i class="fa fa-image"></i>
              </span>
              <span class="panel-title">
                {{ $media->getFilename() }}
              </span>
              <span class="panel-controls">
                <div class="checkbox-delete">
                  <label for="checkboxMedia-{{ $media->id }}">
                    <h2 class="text-default pt5 pn mn"><i class="fa fa-check-circle"></i></h2>
                  </label>
                  <input class="hidden" type="checkbox" id="checkboxMedia-{{ $media->id }}" name="imagestoremove[]" value="{{ $media->id }}">
                </div>
              </span>
            </div>
            <div class="panel-body pn">
            <figure class="mn">
              <picture>
                <source srcset="{{ $media->getPath() }}" media="(min-width: 600px)">
                <img src="{{ $media->getPath() }}" class="img-responsive" title="{{ $media->getFilename() }}">
              </picture>
            </figure>
          </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

