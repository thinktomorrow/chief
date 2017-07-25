@extends('back._layouts.master')

@section('title', '| Edit User')
@section('page-title')
	User management
@stop

@section('topbar-right')
	@can('add_users')
		<button id="btnNewUser" class="btn btn-success">
			<i class="fa fa-plus mr10" aria-hidden="true"></i>
			User toevoegen
		</button>
		<button id="btnCancelUser" class="btn btn-info hidden bringtofront">
			<i class="fa fa-times mr10" aria-hidden="true"></i>
			Annuleren
		</button>
	@endcan
	<section id="OverlayUser" class="overlay" style="display: none;"></section>
@stop

@section('content')
	@include('back.users._partials.users')
@endsection



@push('sidebar')
	 @include('back.users._partials.newuser')
@endpush

@push('custom-scripts')
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.all.min.js') }}"></script>
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.filter.min.js') }}"></script>
    <script>
      $(document).ready(function(){
				$('.table').footable();
        $(document.body).removeClass('sb-r-c');

				// if(localStorage.getItem("UsermanagementList") == "yes") {
				// 	$(".gridview").addClass( "hidden" );
				// 	$(".listview").removeClass( "hidden" );
				// } else {
				// 	$(".listview").addClass( "hidden" );
				// 	$(".gridview").removeClass( "hidden" );
				// }
				//
        // $("#listview").click(function(){
				// 	$(".gridview").addClass( "hidden" );
				// 	$(".listview").removeClass( "hidden" );
				// 	localStorage.setItem("UsermanagementList", "yes");
        // });
				//
        // $("#gridview").click(function(){
				// 	$(".listview").addClass( "hidden" );
				// 	$(".gridview").removeClass( "hidden" );
				// 	localStorage.setItem("UsermanagementList", "no");
        // });

				$("#btnNewUser").click(function(){
					$("#btnNewUser").addClass("hidden");
					$("#btnCancelUser").removeClass("hidden");
					$("body").addClass( "sb-r-o" );
					$("body").removeClass( "sb-r-c" );
					$("body").addClass("sb-l-m");
					$("#OverlayUser").show();
					$( "#focusField" ).focus();
				});

				// $("#btnEditUser").click(function(){
				// 	$("#btnNewUser").addClass("hidden");
				// 	$("#btnCancelUser").removeClass("hidden");
				// 	$("body").addClass( "sb-r-o" );
				// 	$("body").removeClass( "sb-r-c" );
				// 	$("#OverlayUser").show();
				// });

				$("#btnCancelUser").click(function(){
					$("#btnNewUser").removeClass("hidden");
					$("#btnCancelUser").addClass("hidden");
					$("#OverlayUser").hide();
					$("body").removeClass( "sb-r-o" );
					$("body").addClass( "sb-r-c" );
					$("body").removeClass("sb-l-m");
					$(".detail-open").removeClass("detail-open");
					$("body").removeClass("sidebar-media-open");
				});

				$("#OverlayUser").click(function(){
					$("#btnNewUser").removeClass("hidden");
					$("#btnCancelUser").addClass("hidden");
					$("#OverlayUser").hide();
					$("body").removeClass( "sb-r-o" );
					$("body").addClass( "sb-r-c" );
					$("body").removeClass("sb-l-m");
					$(".detail-open").removeClass("detail-open");
					$("body").removeClass("sidebar-media-open");

				});
      });

			$(".showEditUser").click(function(){
				$('.editUser-' + this.dataset.sidebarId).addClass('detail-open');
				// $('.overlay').show(); // Show overlay when detail is active
				$("body").addClass("sidebar-media-open");
				$("body").addClass("sb-l-m");
				$("#btnNewUser").addClass("hidden");
				$("#btnCancelUser").removeClass("hidden");
				$("#OverlayUser").show();
			});


    </script>


@endpush
