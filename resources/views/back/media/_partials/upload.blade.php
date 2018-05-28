<!-- Right Sidebar: UPLOAD IMAGE -->
<aside id="sidebar_right" class="nano uploadForm">

	<!-- Start: Sidebar Right Content -->
	<div class="sidebar-right-content nano-content p15">

		<h4 class="tray-title"> Upload bestand </h4>
		<!-- Image Upload Field -->
		<div class="fileupload fileupload-new admin-form mt20" data-provides="fileupload">
			<div class="fileupload-preview thumbnail m5 mt20 mb30">
				<img src="{{ asset('chief-assets/back/img/placeholder.png')}}" alt="holder">
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form action="{{ route('media.upload') }}" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
		         	<span class="btn-file ph5 btn-group">
								<span class="btn btn-default fileupload-new">Selecteer bestand</span>
								<span class="btn btn-default fileupload-exists mr15">Wijzig</span>
								<input type="file" name="image[]" multiple>
								<button type="submit" class="btn btn-primary btn-file fileupload-exists">Upload bestand <i class="fa fa-upload"></i></button>
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
				<p>Spreadsheet
					<span class="label label-success">.xls</span>
					<span class="label label-success">.xlsx</span>
					<span class="label label-success">.number</span>
				</p>
				<p>Andere
					<span class="label label-danger">.pdf</span>
				</p>
		</div>
	</div>
</aside>