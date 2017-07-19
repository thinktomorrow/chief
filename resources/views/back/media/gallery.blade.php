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
            <div class="panel-body pn showDetail showDetailPanel" id="detailPanel-{{ $media->id }}" data-sidebar-id="{{ $media->id }}">
            <figure class="mn">
              <picture>
                <img src="{{ $media->getImageUrl('large') }}" class="img-responsive" title="{{ $media->getFilename() }}">
              </picture>
            </figure>
          </div>
          </div>
        </div>
        @include('back.media._partials.media-details')
      @endforeach
    </div>
    <div class="row text-center">
      {{ $library->render() }}
    </div>
  </div>
</section>

