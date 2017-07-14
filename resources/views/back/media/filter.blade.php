<div class="mh15 pv15 br-b br-light gallery-filter">
  <div class="row">
    <div class="col-xs-7">
      <div class="mix-controls">
        <div class="btn-group ib mr10">
          <button type="button" class="btn btn-default hidden-xs">
            <span class="fa fa-tag"></span>
          </button>
          <div class="btn-group">
            <fieldset>
              <select id="filter2" style="display: none;">
                <option value="">All Labels</option>
                <option value=".label1">Images</option>
                <option value=".label2">PDF</option>
                <option value=".label2">Spreadsheet</option>
              </select><div class="btn-group"><button type="button" class="multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="All Labels">All Labels <b class="caret"></b></button>
                <ul class="multiselect-container dropdown-menu">
                  <li class="active"><a href="javascript:void(0);"><label class="radio"><input type="radio" value=""> All Labels</label></a></li>
                  <li><a href="javascript:void(0);"><label class="radio"><input type="radio" value=".label1"> Images</label></a></li>
                  <li><a href="javascript:void(0);"><label class="radio"><input type="radio" value=".label2"> PDF</label></a></li>
                  <li><a href="javascript:void(0);"><label class="radio"><input type="radio" value=".label3"> Spreadsheet</label></a></li>
                </ul></div>
              </fieldset>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-xs-5 text-right">
      <div class="btn-group">
        <button type="button" class="btn btn-default mr5">
          <span class="fa fa-trash"></span>
          Delete files
        </button>
        <button type="button" class="btn btn-default mr5" id="showUploadPanel">
          <span class="fa fa-file"></span>
          Upload new file
        </button>
        <button type="button" class="btn btn-default mr5" id="showCropPanel">
          <span class="fa fa-file"></span>
          Crop file
        </button>
      </div>
    </div>
  </div>
</div>