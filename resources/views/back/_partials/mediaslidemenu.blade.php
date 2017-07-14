<!-- Start: Right Sidebar -->
<aside id="sidebar_right" class="nano">

  <!-- Start: Sidebar Right Content -->
  <div class="sidebar-right-content nano-content">

    <!-- Start: Aspect Ratio Toggles -->
    <div class="docs-toggles">
      <!-- <h3 class="page-header">Toggles:</h3> -->
      <div class="btn-group btn-group-justified" data-toggle="buttons" style="margin-bottom: 0px;">
        <label class="btn btn-primary active" data-method="setAspectRatio" data-option="1.7777777777777777" title="Set Aspect Ratio">
          <input class="sr-only" id="aspestRatio1" name="aspestRatio" value="1.7777777777777777" type="radio">
          <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;setAspectRatio&quot;, 16 / 9)">
            16:9
          </span>
        </label>
        <label class="btn btn-primary" data-method="setAspectRatio" data-option="1.3333333333333333" title="Set Aspect Ratio">
          <input class="sr-only" id="aspestRatio2" name="aspestRatio" value="1.3333333333333333" type="radio">
          <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;setAspectRatio&quot;, 4 / 3)">
            4:3
          </span>
        </label>
        <label class="btn btn-primary" data-method="setAspectRatio" data-option="1" title="Set Aspect Ratio">
          <input class="sr-only" id="aspestRatio3" name="aspestRatio" value="1" type="radio">
          <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;setAspectRatio&quot;, 1 / 1)">
            1:1
          </span>
        </label>
        <label class="btn btn-primary" data-method="setAspectRatio" data-option="0.6666666666666666" title="Set Aspect Ratio">
          <input class="sr-only" id="aspestRatio4" name="aspestRatio" value="0.6666666666666666" type="radio">
          <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;setAspectRatio&quot;, 2 / 3)">
            2:3
          </span>
        </label>
        <label class="btn btn-primary" data-method="setAspectRatio" data-option="NaN" title="Set Aspect Ratio">
          <input class="sr-only" id="aspestRatio5" name="aspestRatio" value="NaN" type="radio">
          <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;setAspectRatio&quot;, NaN)">
            Free
          </span>
        </label>
      </div>
    </div>
    <!-- End: Aspect Ratio Toggles -->

    <!-- Start: Image -->
    <img width="100%" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCKbV24ga18bP0XAVCXwmYaZ6v_BEPauuG8eCcSg4H3cIkMcw5_A">
    <!-- End: Image -->

    <!-- Start: Editing Tools -->
    <div class="btn-group btn-group-justified">
      <button class="btn btn-primary btn-sm" data-method="setDragMode" data-option="crop" type="button" title="Crop">
        <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;setDragMode&quot;, &quot;crop&quot;)">
          <span class="fa fa-crop"></span>
        </span>
      </button>
      <button class="btn btn-primary btn-sm" data-method="rotate" data-option="-45" type="button" title="Rotate Left">
        <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;rotate&quot;, -45)">
          <span class="fa fa-rotate-left"></span>
        </span>
      </button>
      <button class="btn btn-primary btn-sm" data-method="rotate" data-option="45" type="button" title="Rotate Right">
        <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;rotate&quot;, 45)">
          <span class="fa fa-rotate-right"></span>
        </span>
      </button>
      <button class="btn btn-primary btn-sm" data-method="reset" type="button" title="Reset">
        <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="$().cropper(&quot;reset&quot;)">
          <span class="fa fa-refresh"></span>
        </span>
      </button>
    </div>
    <!-- End: Editing Tools -->

    <!-- Start: Tabblock -->
    <div class="tab-block sidebar-block br-n">
      <ul class="nav nav-tabs tabs-border nav-justified">
        <li class="active">
          <a href="#sidebar-right-tab1" data-toggle="tab">Info</a>
        </li>
        <li>
          <a href="#sidebar-right-tab2" data-toggle="tab">Tab 2</a>
        </li>
      </ul>
      <div class="tab-content br-n">
        <div id="sidebar-right-tab1" class="tab-pane active">

          <h5 class="title-divider text-muted mb20"> Server Statistics
            <span class="pull-right"> 2013
              <i class="fa fa-caret-down ml5"></i>
            </span>
          </h5>
          <div class="progress mh5">
            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 44%">
              <span class="fs11">DB Request</span>
            </div>
          </div>
          <div class="progress mh5">
            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 84%">
              <span class="fs11 text-left">Server Load</span>
            </div>
          </div>
          <div class="progress mh5">
            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 61%">
              <span class="fs11 text-left">Server Connections</span>
            </div>
          </div>

          <h5 class="title-divider text-muted mt30 mb10">Traffic Margins</h5>
          <div class="row">
            <div class="col-xs-5">
              <h3 class="text-primary mn pl5">132</h3>
            </div>
            <div class="col-xs-7 text-right">
              <h3 class="text-success-dark mn">
                <i class="fa fa-caret-up"></i> 13.2% </h3>
            </div>
          </div>

          <h5 class="title-divider text-muted mt25 mb10">Database Request</h5>
          <div class="row">
            <div class="col-xs-5">
              <h3 class="text-primary mn pl5">212</h3>
            </div>
            <div class="col-xs-7 text-right">
              <h3 class="text-success-dark mn">
                <i class="fa fa-caret-up"></i> 25.6% </h3>
            </div>
          </div>

          <h5 class="title-divider text-muted mt25 mb10">Server Response</h5>
          <div class="row">
            <div class="col-xs-5">
              <h3 class="text-primary mn pl5">82.5</h3>
            </div>
            <div class="col-xs-7 text-right">
              <h3 class="text-danger mn">
                <i class="fa fa-caret-down"></i> 17.9% </h3>
            </div>
          </div>

          <h5 class="title-divider text-muted mt40 mb20"> Server Statistics
            <span class="pull-right text-primary fw600">USA</span>
          </h5>


        </div>
        <div id="sidebar-right-tab2" class="tab-pane">
          Sidebar Tab <b>Two</b> Content
        </div>
      </div>
      <!-- end: .tab-content -->
    </div>
    <!-- End: Tabblock -->
  </div>
</aside>
<!-- End: Right Sidebar -->
