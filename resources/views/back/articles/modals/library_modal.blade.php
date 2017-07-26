<div id="modal-panel" class="popup-basic popup-full bg-none mfp-with-anim mfp-hide media-gallery">
	<div class="panel">
		<div class="panel-heading">Mediagalerij</div>
		<div class="panel-body">
			@foreach($assets as $media)
				<div class="mix col-sm-6 col-md-4 col-lg-2 {{ $loop->first ? 'selected' : '' }}" data-asset-id="{{ $media->id }}">
					<div class="panel media pbn">
						<div class="panel-heading">
							<span class="panel-icon pull-left">
								<i class="fa fa-image"></i>
							</span>
							<span class="panel-title overflow-ellipsis">
								{{ $media->getFilename() }}
							</span>
							<span class="panel-controls">
								<div class="checkbox-delete">
									<label class="hidden" for="checkboxMedia-{{ $media->id }}">
										<h3 class="text-default pt5 pn mn"><i class="fa fa-check-circle"></i></h3>
									</label>
									<input class="" type="radio" value="{{ $media->id }}">
								</div>
							</span>
						</div>
						<div class="panel-body pn">
							<figure class="gallery-item mn mh-100">
								<picture>
									<img src="{{ $media->getImageUrl('large') }}" class="img-responsive"
									title="{{ $media->getFilename() }}">
								</picture>
							</figure>
						</div>
					</div>
				</div>
			@endforeach
		</div>
		<div class="panel-footer">
			<button class="btn btn-default addAsset">Selecteer</button>
		</div>
	</div>
</div>