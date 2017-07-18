<div class="mh15 pv15 br-b br-light gallery-filter">
  <div class="row">
    <div class="col-xs-4">
      <div class="mix-controls">
        <div class="btn-group">
          <div class="btn-group">
            <button type="button" class="btn btn-default">
              <span class="fa fa-fw fa-image"></span> Afbeeldingen
            </button>
            <button type="button" class="btn btn-default">
              <span class="fa fa-fw fa-file-pdf-o"></span> Pdf
            </button>
            <button type="button" class="btn btn-default">
              <span class="fa fa-fw fa-file-excel-o"></span> Excel
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-8 text-right">
      <div class="btn-group">
        <div class="deleteMedia hidden">
          <div class="deleteActions hidden">
            <span class="btn btn-danger disabled">Bestanden verwijderen ?</span>
            <a class="btn btn-primary noDelete"> <i class="fa fa-times"></i> </a>
            <button type="submit" class="btn btn-default btnDelete mr5"> <i class="fa fa-check"></i>  </button>
          </div>
          <div class="btn btn-warning mr5 showDeleteUptions">
            <span class="fa fa-trash"></span>
            Bestanden verwijderen
          </div>
        </div>
      </div>
      @if(!$library->isEmpty())
      <div class="btn btn-default selectBtn">
        <label for="selectAllMedia" class="mn">
          Selecteer alle bestanden
          <i class="fa fa-check-circle hidden"></i>
        </label>
        <input class="hidden" type="checkbox" id="selectAllMedia">
      </div>
      @endif
      <button type="button" class="btn btn-default mr5" id="showUploadPanel">
        <span class="fa fa-upload"></span>
        Upload nieuw bestand
      </button>
    </div>
  </div>
</div>