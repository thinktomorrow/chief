<!-- Right Sidebar: IMAGE DETAIL -->
<aside id="sidebar_right" class="imageDetail-{{ $media->id }} nano affix">
	<!-- Start: Sidebar Right Content -->
	<div class="sidebar-right-content nano-content pn">
		<div class="image-preview">
    	<img width="100%" src="{{ $media->getImageUrl('full') }}" title="{{ $media->getFilename() }}">
		</div>
    <!-- Start: Tabblock -->
    <div class="tab-block sidebar-block br-n">
      <ul class="nav nav-tabs tabs-border nav-justified">
        <li class="active">
          <a href="#sidebar-right-tab1" data-toggle="tab">Detail</a>
        </li>
        <li>
          <a href="#sidebar-right-tab2" data-toggle="tab">All afmetingen</a>
        </li>
      </ul>
      <div class="tab-content br-n">
        <div id="sidebar-right-tab1" class="tab-pane active">

          <ul class="icon-list">
            <li>
              <b> Bestandsnaam:</b> {{ $media->getFilename() }}
            </li>
            <li>
              <b> Soort bestand:</b> {{ $media->getMimeType() }}
            </li>
            <li>
              <b> Geüpload up:</b> {{ $media->created_at }}
            </li>
            <li>
              <b> Bestandsgrootte:</b> {{ $media->getSize() }}
            </li>
            <li>
              <b> Afmetingen:</b> {{ $media->getDimensions() }}
            </li>
            <li>
              <b> Geüpload door:</b>  /
            </li>
          </ul>

          <div class="panel">
            <div class="panel-heading" style="height: auto; min-height: auto;">
               <span class="panel-icon"><i class="fa fa-clipboard"></i></span>
               <span class="panel-title"> Image path</span>
            </div>
            <div class="panel-body" style="word-wrap: break-word;">
              {{ url($media->getPathForSize('')) }}
            </div>
          </div>
        </div>
        <div id="sidebar-right-tab2" class="tab-pane gallery">
					<div class="list-group list-group-links">
		          <a href="{{ $media->getImageUrl('thumb') }}" title="Thumbnail" class="list-group-item">
								Thumbnail {{ $media->getDimensions('thumb') }}
									<span class="label badge-primary">Bekijk afbeelding</span>
							</a>
							<hr class="short alt mv5">
		          <a href="{{ $media->getImageUrl('medium') }}" title="Medium" class="list-group-item">
								Medium {{ $media->getDimensions('thumb') }}
								<span class="label badge-primary">Bekijk afbeelding</span>
							</a>
							<hr class="short alt mv5">
		          <a href="{{ $media->getImageUrl('large') }}" title="Large" class="list-group-item">
								Large {{ $media->getDimensions('thumb') }}
								<span class="label badge-primary">Bekijk afbeelding</span>
							</a>
							<hr class="short alt mv5">
		          <a href="{{ $media->getImageUrl('full') }}" title="Full" class="list-group-item">
								Full {{ $media->getDimensions('thumb') }}
								<span class="label badge-primary">Bekijk afbeelding</span>
							</a>
							<hr class="short alt mv5">
							<a href="{{ $media->getImageUrl() }}" title="Originele afbeelding" class="list-group-item">
									Origineel {{ $media->getDimensions('thumb') }}
									<span class="label badge-primary">Bekijk afbeelding</span>
							</a>
						</div>
        </div>
      </div>
      <!-- end: .tab-content -->
    </div>
    <!-- End: Tabblock -->
  </div>
</aside>
<!-- End: Right Sidebar -->