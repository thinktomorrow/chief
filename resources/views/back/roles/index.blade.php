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
	@include('back.roles._partials.roles')
@stop

@push('sidebar')
   @include('back.roles._partials.newrole')
@endpush

@push('custom-scripts')
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.all.min.js') }}"></script>
		<script src="{{ asset('assets/back/theme/vendor/plugins/footable/js/footable.filter.min.js') }}"></script>
    <script>
      $(document).ready(function(){
				$('.table').footable();
        $(document.body).removeClass('sb-r-c');

				$("#btnNewRole").click(function(){
					$("#btnNewRole").addClass("hidden");
					$("#btnCancelRole").removeClass("hidden");
					$("body").addClass( "sb-r-o" );
					$("body").removeClass( "sb-r-c" );
					$("#OverlayRole").show();
					//set focus on first input field
					$( "#OverlayRole" ).focus();
				});

				$("#btnCancelRole").click(function(){
					$("#btnNewRole").removeClass("hidden");
					$("#btnCancelRole").addClass("hidden");
					$("#OverlayRole").hide();
					$("body").removeClass( "sb-r-o" );
					$("body").addClass( "sb-r-c" );
				});

				$("#OverlayRole").click(function(){
					$("#btnNewRole").removeClass("hidden");
					$("#btnCancelRole").addClass("hidden");
					$("#OverlayRole").hide();
					$("body").removeClass( "sb-r-o" );
					$("body").addClass( "sb-r-c" );
				});
      });


    </script>

	@endpush
