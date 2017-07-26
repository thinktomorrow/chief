<!-- Right Sidebar: UPLOAD IMAGE -->
<aside id="sidebar_right" class="nano uploadForm">

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
                            <input id="gallerupload" class="hidden" type="text" name="asset_id" value="">
							{!! \Chief\Models\Asset::typeField('thumbnail') !!}
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

@include('back.articles.modals.library_modal')

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
			$('#galleryupload').val($('.selected').data('asset-id'));
			var magnificPopup = $.magnificPopup.instance;

			magnificPopup.close();
		})

	});
</script>

@endpush