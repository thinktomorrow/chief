<!-- Right Sidebar: UPLOAD IMAGE -->
<aside id="sidebar_right" class="nano bannerUpload-{{ $locale }}">

    <!-- Start: Sidebar Right Content -->
    <div class="sidebar-right-content nano-content p15">

        <h4 class="tray-title"> Upload Image </h4>
        <!-- Image Upload Field -->
        <div class="fileupload fileupload-new admin-form mt20" data-provides="fileupload">
            <div class="fileupload-preview thumbnail m5 mt20 mb30">
                <img src="{{ asset('assets/back/img/placeholder.png')}}" alt="holder">
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <form action="{{ route('article.upload', $article->id) }}" method="POST"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <span class="btn-file ph5 btn-group">
                            <span class="btn btn-default fileupload-new">Selecteer bestand</span>
                            <button class="libraryselect" data-effect="mfp-zoomIn">Selecteer uit bibliotheek</button>
                            <span class="btn btn-default fileupload-exists mr15">Wijzig</span>
                            <input type="file" name="image" accept="image/*">
                            {!! \Chief\Models\Asset::typeField('banner') !!}
                            {!! \Chief\Models\Asset::localeField($locale) !!}
                            <button type="submit" class="btn btn-primary btn-file fileupload-exists">Upload bestand <i
                                        class="fa fa-upload"></i></button>
		                </span>
                    </form>

                </div>
            </div>
        </div>
        <hr class="alt short">
        <div class="tray">
            <h5>Toegelaten bestandsformaten</h5>
            <p>Afbeeldingen:
                <span class="label label-primary">.jpg</span>
                <span class="label label-primary">.png</span>
                <span class="label label-primary">.jpeg</span>
                <span class="label label-primary">.gif</span>
            </p>
        </div>
    </div>
</aside>

<div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        @foreach($assets as $media)
            <div class="mix col-sm-6 col-md-6 col-lg-3 {{ $loop->first ? 'selected' : '' }}" data-asset-id="{{ $media->id }}">
                <div class="panel media pbn">
                    <div class="panel-heading">
					<span class="panel-icon pull-left">
						<i class="fa fa-image"></i>
					</span>
                        <span class="panel-title overflow-ellipsis">
                        {{ $media->getFilename() }}
                    </span>
                    </div>
                    <div class="panel-body pn">
                        <figure class="gallery-item mn">
                            <picture>
                                <img src="{{ $media->getImageUrl('large') }}" class="img-responsive"
                                     title="{{ $media->getFilename() }}">
                            </picture>
                        </figure>
                    </div>
                </div>
            </div>
        @endforeach
        <button class="addAsset">Selecteer</button>
    </div>
</div>
@push('custom-scripts')

<script>
	$(document).ready(function () {
		$('.libraryselect').on('click', function (e) {
			e.preventDefault();
			$.magnificPopup.open({
				removalDelay: 500, //delay removal by X to allow out-animation,
				items: {
					src: $('#modal-panel')
				}
			});
		});

		$('.addAsset').on('click', function (){
			$('#galleryupload-banner').val($('.selected').data('asset-id'));
			var magnificPopup = $.magnificPopup.instance;

			magnificPopup.close();
		})

	});
</script>

@endpush