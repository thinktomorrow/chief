<!-- Right Sidebar: UPLOAD IMAGE -->
<aside id="sidebar_right" class="nano">

	<!-- Start: Sidebar Right Content -->
	<div class="sidebar-right-content nano-content p15">

		<h4 class="tray-title"> Upload New Image </h4>

		<!-- Image Upload Field -->
		<div class="fileupload fileupload-new admin-form mt20" data-provides="fileupload">
			<div class="fileupload-preview thumbnail m5 mt20 mb30">
				<img src="{{ asset('assets/back/img/placeholder.png')}}" alt="holder">
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form action="{{ route('media.upload') }}" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
		         	<span class="btn-file ph5 btn-group">
								<span class="btn btn-default fileupload-new">Selecteer bestand</span>
								<span class="btn btn-default fileupload-exists mr15">Wijzig</span>
								<input type="file" name="image[]" multiple accept="image/*">
								<button type="submit" class="btn btn-primary btn-file fileupload-exists">Upload bestand <i class="fa fa-upload"></i></button>
		           </span>
					</form>
				</div>
			</div>
		</div>
	</div>
</aside>