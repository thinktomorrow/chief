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
   @include('back.media._partials.media-details')
   @include('back.media._partials.upload')
@stop

@push('custom-scripts')
<script>
$(document).ready(function(){

  $(document.body).removeClass('sb-r-c');

  $("#showDetailPanel").click(function(){
    $(document.body).toggleClass('detail-open');
  });

  $("#showUploadPanel").click(function(){
    $(document.body).toggleClass('upload-open');
  });


  // SHOW OR HIDE DELETE BUTTON
  $('.showDeleteUptions').click(function(){
    $('.deleteActions').removeClass('hidden');
    $('.showDeleteUptions').addClass('hidden');
  });
  $('.noDelete').click(function(){
    $('.deleteActions').addClass('hidden');
    $('.showDeleteUptions').removeClass('hidden');
  });

  // Get universal class for the checkbox and put it in a variable
  var getCheckbox = $(".checkbox-delete > input:checkbox");

  function countCheckboxes(){
    // When on or more checkbox is checked, show the deleteButton
    var selectedCheckbox = $(":checkbox:checked").length;
    console.log(selectedCheckbox);
    if (selectedCheckbox > 0){
      $('.deleteMedia').removeClass('hidden')
    }
    else{
      $('.deleteMedia').addClass('hidden')
    }
  };

  getCheckbox.change(function () {
    countCheckboxes();
    if ($(this).is(":checked")) {
      $(this).closest(".media").addClass('selected');
      $(this).closest(".checkbox-delete").addClass('show');
    }
    else {
      $(this).closest(".media").removeClass('selected');
      $(this).closest(".checkbox-delete").removeClass('show');
      $('#selectAllMedia').prop('checked', false);
    };
  });

  // CHECKBOX TO SELECT ALL IMAGES
  $('#selectAllMedia').change(function(){
    if ($(this).is(":checked")) {
      getCheckbox.closest(".media").addClass('selected');
      getCheckbox.closest(".checkbox-delete").addClass('show');
      getCheckbox.prop('checked',true);
      $('.selectBtn .fa').removeClass('hidden');
    }
    else{
      getCheckbox.closest(".media").removeClass('selected');
      getCheckbox.closest(".checkbox-delete").removeClass('show');
      getCheckbox.prop('checked',false);
    }
    countCheckboxes();
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
