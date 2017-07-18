<!-- Right Sidebar: IMAGE DETAIL -->
<aside id="sidebar_right" class="nano affix">

	<!-- Start: Sidebar Right Content -->
	<div class="sidebar-right-content nano-content pn">

    <!-- Start: Image -->
    <img width="100%" src="{{ $media->getPathForSize('large') }">
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
              <b> Bestandsnaam:</b> benstinkt.jpg
            </li>
            <li>
              <b> Soort bestand:</b> image/jpeg
            </li>
            <li>
              <b> Geüpload up:</b> Juni 26, 2017
            </li>
            <li>
              <b> Bestandsgrootte:</b> 690kb
            </li>
            <li>
              <b> Afmetingen:</b> 690 x 690
            </li>
            <li>
              <b> Geüpload door:</b> Bob Dries
            </li>
          </ul>

          <div class="panel">
            <div class="panel-heading" style="height: auto; min-height: auto;">
               <span class="panel-icon"><i class="fa fa-clipboard"></i></span>
               <span class="panel-title"> Image path</span>
            </div>
            <div class="panel-body" style="word-wrap: break-word;">
              http://thinktomorrow.be/assets/img/benstinkt.jpg
            </div>
          </div>
        </div>
        <div id="sidebar-right-tab2" class="tab-pane">

        </div>
      </div>
      <!-- end: .tab-content -->
    </div>
    <!-- End: Tabblock -->
  </div>
</aside>
<!-- End: Right Sidebar -->
