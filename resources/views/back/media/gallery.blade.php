<style>
.checkbox-delete{
  display: none;
}
.media-gallery .media{
  transition: all 0.15s ease-in-out;
  border: 5px solid #eeeeee;
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
.media.selected .fa{
  color: #4a89dc;
}
.overflow-ellipsis{
  width: 75%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: inline-block;
}
figure{
  height: 250px;
  overflow: hidden;
  position: relative;
}
figure img{
  position: absolute;
  transform: translate(-50%,-50%);
  top: 50%; left: 50%;
}
</style>

<section class="media-gallery mh15 pv15">
  <div id="mix-container">
    <div class="row">
      @if($library->isEmpty())
      <div class="text-center">
        <img src="{{ asset('assets/back/img/placeholder.png')}}" alt="holder">
      </div>
      @endif
      @foreach($library as $media)
        <!-- image thumb -->
        <div class="mix col-sm-6 col-md-6 col-lg-3">
          <div class="panel media pbn">
            <div class="panel-heading">
              <span class="panel-icon pull-left">
                <i class="fa fa-image"></i>
              </span>
              <span class="panel-title overflow-ellipsis">
                {{ $media->getFilename() }}
              </span>
              <span class="panel-controls">
                <div class="checkbox-delete">
                  <label for="checkboxMedia-{{ $media->id }}">
                    <h3 class="text-default pt5 pn mn"><i class="fa fa-check-circle"></i></h3>
                  </label>
                  <input class="hidden" type="checkbox" id="checkboxMedia-{{ $media->id }}" name="imagestoremove[]" value="{{ $media->id }}">
                </div>
              </span>
            </div>
            <div class="panel-body pn">
            <figure class="mn">
              <picture>
                <img src="{{ $media->getPathForSize('large') }}" class="img-responsive" title="{{ $media->getFilename() }}">
              </picture>
            </figure>
          </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="row text-center">
      {{ $library->render() }}
    </div>
  </div>
</section>

