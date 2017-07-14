@extends('back._layouts.master')

@section('page-title')
    Mediagallerij
@stop

@section('topbar-right')

@stop

@section('content')
  @include('back.media.filter')
  @include('back.media.gallery')
@stop

@section('sidebar')
  @include('back.media._partials.upload')
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
