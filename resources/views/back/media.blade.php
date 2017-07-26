@extends('back._layouts.master')

@section('page-title')
Mediabibliotheek
@stop

@section('topbar-right')
<button type="button" class="btn btn-success mr5" id="showUploadPanel">
  <i class="fa fa-upload mr10"></i>
  Upload nieuw bestand
</button>
@stop

@section('content')
  <form action="{{ route('media.remove') }}" method="POST">
    {{ csrf_field() }}
    @include('back.media._partials.filter')
    @include('back.media.index')
  </form>
@stop

@push('sidebar')
  @include('back.media._partials.upload')
@endpush

@push('custom-scripts')
{{-- USED FOR THE MEDIA FILTERS --}}
<script src="{{ asset('assets/back/theme/vendor/plugins/mixitup/jquery.mixitup.js') }}"></script>

<script>
$(document).ready(function(){

  $(document.body).removeClass('sb-r-c');
// ***********************************
// ** SHOW DETAIL PANEL OF AN IMAGE **
// ***********************************
  $(".showDetailPanel").click(function(){
    $('.imageDetail-' + this.dataset.sidebarId).addClass('detail-open');
    $('.overlay').show(); // Show overlay when detail is active
    $(document.body).addClass('sidebar-media-open');
  });

  $(".overlay").click(function(){
    $('#sidebar_right.detail-open').removeClass('detail-open');
    $('.overlay').hide(); // Show overlay when detail is active
    $(document.body).removeClass('sidebar-media-open');
  });
  $("#showUploadPanel").click(function(){
    $(document.body).toggleClass('upload-open');
  });

// Get universal class for the checkbox and put it in a variable
	var getCheckbox = $(".checkbox-delete > input:checkbox");

  // SHOW OR HIDE DELETE BUTTON
  $('.showDeleteUptions').click(function(){
    $('.deleteActions').removeClass('hidden');
    $('.deleteActions span').text($(".media-gallery :checkbox:checked").length + ' bestanden verwijderen?');
    $('.showDeleteUptions').addClass('hidden');
  });
  $('.noDelete').click(function(){
    $('.deleteActions').addClass('hidden');
    $('.showDeleteUptions').removeClass('hidden');
  });

// ***********************************
// ** SELECT IMAGES TO DELETE **
// ***********************************

  function countCheckboxes(){
    // When on or more checkbox is checked, show the deleteButton
    var selectedCheckbox = $(":checkbox:checked").length;
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
      $('.selectBtn label span').text('De-selecteer alle bestanden');
    }
    else{
      getCheckbox.closest(".media").removeClass('selected');
      getCheckbox.closest(".checkbox-delete").removeClass('show');
      getCheckbox.prop('checked',false);
      $('.selectBtn .fa').addClass('hidden');
      $('.selectBtn label span').text('Selecteer alle bestanden');
    }
    countCheckboxes();
  });

// ***********************************
// ** FILE UPLOAD **
// ***********************************
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

	$('#mix-container').mixItUp({
		animation: {
			duration: 400,
			effects: 'fade translateZ(-360px) stagger(45ms)',
			easing: 'ease'
		},
	});
  //End of document ready function
});

// ***************************
// ** LIGHTBOX OF AN IMAGE **
// ***************************
$('.image-preview img').magnificPopup({
  type: 'image',
  callbacks: {
    beforeOpen: function(e) {
      // we add a class to body to indicate overlay is active
      // We can use this to alter any elements such as form popups
      // that need a higher z-index to properly display in overlays
      $('body').addClass('mfp-bg-open');

      // Set Magnific Animation
      this.st.mainClass = 'mfp-zoomIn';

      // Inform content container there is an animation
      this.contentContainer.addClass('mfp-with-anim');
    },
    afterClose: function(e) {

      setTimeout(function() {
        $('body').removeClass('mfp-bg-open');
        $(window).trigger('resize');
      }, 1000)

    },
    elementParse: function(item) {
      // Function will fire for each target element
      // "item.el" is a target DOM element (if present)
      // "item.src" is a source that you may modify
      item.src = item.el.attr('src');
    },
  },

  overflowY: 'scroll',
  removalDelay: 200, //delay removal by X to allow out-animation
  prependTo: $('#content_wrapper')
});
$('.gallery').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
});
</script>
@endpush