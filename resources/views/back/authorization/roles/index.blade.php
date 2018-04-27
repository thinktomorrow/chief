@extends('back._layouts.master')

@section('title', '| Edit Roles')

@section('page-title')
	Role management
@stop

@section('topbar-right')
	@can('add_users')
		<button id="btnNewRole" class="btn btn-success">
			<i class="fa fa-plus mr10" aria-hidden="true"></i>
			Rol toevoegen
		</button>
		<button id="btnCancelRole" class="btn btn-info hidden bringtofront">
			<i class="fa fa-times mr10" aria-hidden="true"></i>
			Annuleren
		</button>
	@endcan
	<section id="OverlayRole" class="overlay" style="display: none;"></section>
@stop

@section('content')
	@include('back.authorization.roles._partials.roles')
@stop

@push('sidebar')
	 @include('back.authorization.roles._partials.newrole')
@endpush

@push('custom-scripts')
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.all.min.js') }}"></script>
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.filter.min.js') }}"></script>

		<!-- Fancytree Plugin -->
		<script src="{{ asset('assets/back/theme/vendor/plugins/fancytree/jquery.fancytree-all.min.js') }}"></script>

    <script>
      $(document).ready(function(){
				$('.table').footable();
        $(document.body).removeClass('sb-r-c');

				$("#btnNewRole").click(function(){
					$("#btnNewRole").addClass("hidden");
					$("#btnCancelRole").removeClass("hidden");
					$("body").addClass( "sb-r-o" );
					$("body").removeClass( "sb-r-c" );
					$("body").addClass("sb-l-m");
					$("#OverlayRole").show();
					//set focus on first input field
					$( "#OverlayRole" ).focus();
				});

				$("#btnCancelRole").click(function(){
					$("#btnNewRole").removeClass("hidden");
					$("#btnCancelRole").addClass("hidden");
					$("#OverlayRole").hide();
					$("body").removeClass( "sb-r-o" );
					$("body").removeClass("sb-l-m");
					$("body").addClass( "sb-r-c" );
					$(".detail-open").removeClass("detail-open");
					$("body").removeClass("sidebar-media-open");
					location.reload();
				});

				$("#OverlayRole").click(function(){
					$("#btnNewRole").removeClass("hidden");
					$("#btnCancelRole").addClass("hidden");
					$("#OverlayRole").hide();
					$("body").removeClass( "sb-r-o" );
					$("body").removeClass("sb-l-m");
					$("body").addClass( "sb-r-c" );
					$(".detail-open").removeClass("detail-open");
					$("body").removeClass("sidebar-media-open");
					location.reload();
				});

				$(".showEditRole").click(function(){
					$('.editRole-' + this.dataset.sidebarId).addClass('detail-open');
					// $('.overlay').show(); // Show overlay when detail is active
					$("body").addClass("sidebar-media-open");
					$("body").addClass("sb-l-m");
					$("#btnNewRole").addClass("hidden");
					$("#btnCancelRole").removeClass("hidden");
					$("#OverlayRole").show();
				});

				$('.treeview-item').each(function () {
						$('#' + this.id).fancytree({
							autoCollapse: true,
							checkbox: false, // Show checkboxes.
							clickFolderMode: 2, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
						});
				});

      });
    </script>

	@endpush
