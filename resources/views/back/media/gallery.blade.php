<section class="page-gallery pv15">
  <div id="mix-container">
    <div class="row">
      @foreach($library as $media)
        <!-- image thumb -->
        <div class="mix col-md-3">
          <div class="panel p6 pbn">
            <div class="of-h">
              <picture>
                <source srcset="{{ $media->getPath() }}" media="(min-width: 600px)">
                <img src="{{ $media->getPath() }}" class="img-responsive" title="{{ $media->getFilename() }}">
              </picture>
              <figcaption class="row table-layout">
                <div class="col-xs-8 va-m pln">
                  <h6>{{ $media->getFilename() }}</h6>
                </div>
                <div class="col-xs-4 text-right va-m prn">
                  <span class="fa fa-image fs12"></span>
                </div>
              </figcaption>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

