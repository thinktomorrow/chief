<!-- Right Sidebar: UPLOAD IMAGE -->
<aside id="sidebar_right" class="nano pdfUpload-{{ $locale }}">

    <!-- Start: Sidebar Right Content -->
    <div class="sidebar-right-content nano-content p15">

        <h4 class="tray-title"> Upload pdf </h4>
        <!-- Image Upload Field -->
        <div class="fileupload fileupload-new admin-form mt20" data-provides="fileupload">
            <div class="fileupload-preview thumbnail m5 mt20 mb30">
                <img src="{{ asset('assets/back/img/placeholder.png')}}" alt="holder">
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <form action="{{ route('article.upload', $article->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <span class="btn-file ph5 btn-group">
                            <span class="btn btn-default fileupload-new">Selecteer pdf</span>
                            <span class="btn btn-default fileupload-exists mr15">Wijzig</span>
                            <input type="file" name="image" accept="application/pdf">
                            {!! \Chief\Models\Asset::typeField('pdf') !!}
                            {!! \Chief\Models\Asset::localeField($locale ) !!}
                            <button type="submit" class="btn btn-primary btn-file fileupload-exists">Upload pdf <i class="fa fa-upload"></i></button>
		                </span>
                    </form>
                </div>
            </div>
        </div>
        <hr class="alt short">
        <div class="tray">
            <h5>Toegelaten bestandsformaten</h5>
            <p>
                <span class="label label-danger">.pdf</span>
            </p>
        </div>
    </div>
</aside>