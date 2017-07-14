@extends('back._layouts.master')

@section('page-title')
    Artikels
@stop

@section('topbar-right')

@stop

@section('content')
<section id="content" class="table-layout">
<div class="tray">
  @include('back.media.filter')
  @include('back.media.gallery')
</div>
<div class="tray tray-center">
  @include('back.media._partials.upload')
</div>
  <!-- @include('back/_partials/mediaslidemenu') -->

@stop
@section('topbar-right')
<script>
$(function() {
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
@stop
