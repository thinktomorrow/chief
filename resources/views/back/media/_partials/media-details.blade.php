<!-- Right Sidebar: IMAGE DETAIL -->
<aside id="sidebar_right" class="imageDetail-{{ $media->id }} nano affix">
	<!-- Start: Sidebar Right Content -->
	<div class="sidebar-right-content nano-content pn">
    <!-- Start: Image -->
    <img width="100%" src="{{ $media->getPathForSize('full') }}">
    <!-- End: Image -->

    <!-- Start: Tabblock -->
    <div class="tab-block sidebar-block br-n">
      <ul class="nav nav-tabs tabs-border nav-justified">
        <li class="active">
          <a href="#sidebar-right-tab1" data-toggle="tab">Info</a>
        </li>
        <li>
          <a href="#sidebar-right-tab2" data-toggle="tab">All Sizes</a>
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
              {{ url($media->getPathForSize('medium')) }}
            </div>
          </div>
        </div>
        <div id="sidebar-right-tab2" class="tab-pane">
          <img width="100%" src="{{ $media->getPathForSize() }}">
          <img src="{{ $media->getPathForSize('thumb') }}">
          <img src="{{ $media->getPathForSize('medium') }}">
          <img src="{{ $media->getPathForSize('large') }}">
          <img src="{{ $media->getPathForSize('full') }}">
        </div>
      </div>
      <!-- end: .tab-content -->
    </div>
    <!-- End: Tabblock -->
  </div>
</aside>
<!-- End: Right Sidebar -->
