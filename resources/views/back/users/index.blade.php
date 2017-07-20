@extends('back._layouts.master')

@section('title', '| Edit User')

@section('content')

	@section('page-title')
	User management
	@stop

	@section('topbar-right')
		@can('add_users')
			<button id="btnNewUser" class="btn btn-success">
				<i class="fa fa-plus mr10" aria-hidden="true"></i>
				User toevoegen
			</button>
			<button id="btnCancelUser" class="btn btn-info hidden">
				<i class="fa fa-times mr10" aria-hidden="true"></i>
				Annuleren
			</button>
		@endcan
	@stop

	@include('back.users._partials.users')
@endsection

<section id="OverlayUser" class="overlay" style="display: none;"></section>

@section('sidebar')
   @include('back.users._partials.newuser')
@stop

@push('custom-scripts')
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.all.min.js') }}"></script>
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.filter.min.js') }}"></script>
    <script>
      $(document).ready(function(){
				$('.table').footable();
        $(document.body).removeClass('sb-r-c');

				if(localStorage.getItem("UsermanagementList") == "yes") {
					$(".gridview").addClass( "hidden" );
					$(".listview").removeClass( "hidden" );
				} else {
					$(".listview").addClass( "hidden" );
					$(".gridview").removeClass( "hidden" );
				}

        $("#listview").click(function(){
					$(".gridview").addClass( "hidden" );
					$(".listview").removeClass( "hidden" );
					localStorage.setItem("UsermanagementList", "yes");
        });

        $("#gridview").click(function(){
					$(".listview").addClass( "hidden" );
					$(".gridview").removeClass( "hidden" );
					localStorage.setItem("UsermanagementList", "no");
        });

				$("#btnNewUser").click(function(){
					$("#btnNewUser").addClass("hidden");
					$("#btnCancelUser").removeClass("hidden");
					$("body").addClass( "sb-r-o" );
					$("body").removeClass( "sb-r-c" );
					$("#OverlayUser").show();
					//set focus on first input field
					$( "#focusField" ).focus();
				});

				$("#btnCancelUser").click(function(){
					$("#btnNewUser").removeClass("hidden");
					$("#btnCancelUser").addClass("hidden");
					$("body").removeClass( "sb-r-o" );
					$("body").addClass( "sb-r-c" );
				});


      });


    </script>


@endpush
