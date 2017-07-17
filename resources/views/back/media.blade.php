@extends('back._layouts.master')

@section('page-title')
    Mediagalerij
@stop

@section('topbar-right')

@stop

@section('content')
  <form action="{{ route('media.remove') }}" method="POST">
    {{ csrf_field() }}
    @include('back.media._partials.filter')
    @include('back.media.gallery')
  </form>
@stop

@section('sidebar')
  @include('back.media._partials.upload')
   {{--@include('back.media._partials.mediaslidemenu')--}}
@stop

@push('custom-scripts')
    <script>
      $(document).ready(function(){

        $(document.body).removeClass('sb-r-c');

        $("#showUploadPanel").click(function(){
          $(document.body).toggleClass('sb-r-o');
        });
        $("#showCropPanel").click(function(){
          $(document.body).toggleClass('sb-r-o');
        });


        $('.checkbox-delete > input:checkbox').change(function () {
        		if ($(this).is(":checked")) {
        				$(this).closest(".media").addClass('selected');
        				$(this).closest(".checkbox-delete").addClass('show');
                $('.btnDelete').removeClass('hidden');
        		}
        		else {
        				$(this).closest(".media").removeClass('selected');
                $(this).closest(".checkbox-delete").removeClass('show');
                $('.btnDelete').addClass('hidden');
        		};
        });

        // give file-upload preview onclick functionality
        var fileUpload = $('.fileupload-preview');
        if (fileUpload.length) {
          fileUpload.each(function(i, e) {
            var fileForm = $(e).parents('.fileupload').find('.btn-file > input');
            $(e).on('click', function() {
              fileForm.click();
            });
          });
        }
      });
    </script>


@endpush
