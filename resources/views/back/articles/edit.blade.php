@extends('back._layouts.master')

@section('custom-styles')
<link rel="stylesheet" href="{{ asset('assets/back/vendor/redactor/redactor.css') }}">
@stop

@push('custom-scripts')
<script src="{{ asset('assets/back/vendor/redactor/redactor.js') }}"></script>
<script>
;(function ($) {

  //$('.redactor-editor').redactor({
  //focus: true,
  //pastePlainText: true,
  //buttons: ['html', 'formatting', 'bold', 'italic',
  //'unorderedlist', 'orderedlist', 'outdent', 'indent',
  //'link', 'alignment','image','horizontalrule'],
  {{--imageUpload: '{{ route('back.articles.fileupload') }}?id={{ $article->id }}&_token={{ csrf_token() }}',--}}
  {{--image_dir: '{{ $article::getContentImageDirectory() }}',--}}
  //imageUploadErrorCallback: function(json)
  //{
  //$('body').prepend('<div class="alert alert-top alert-danger alert-dismissable"><button type="button" class="cl data-dismiss="alert" aria-hidden="true">&times;</button><div class="container">'+json.message+'</div></div>');--}}
  //}
  //});

  //// Delete modal
  //$("#remove-article-toggle").magnificPopup();

  //// Sortable
  //var el = document.getElementsByClassName('sortable')[0];
  //var sortable = Sortable.create(el);

  //// Initiate our cropper
  //new Cropper();

  $("#showUploadPanel").click(function(){
    $(document.body).toggleClass('upload-open');
  });

  $("[id^='showPdfUploadPanel-'], [id*='showPdfUploadPanel-']").click(function(){
    $('.pdfUpload-' + this.dataset.uploadLocale).addClass('detail-open');
    $('.overlay').show(); // Show overlay when detail is active
    $(document.body).addClass('sidebar-media-open');
  });

  $("[id^='showBannerUploadPanel-'], [id*='showBannerUploadPanel-']").click(function(){
    $('.bannerUpload-' + this.dataset.uploadLocale).addClass('detail-open');
    $('.overlay').show(); // Show overlay when detail is active
    $(document.body).addClass('sidebar-media-open');
  });

  $(".overlay").click(function(){
    $('#sidebar_right.detail-open').removeClass('detail-open');
    $('.overlay').hide(); // Show overlay when detail is active
    $(document.body).removeClass('sidebar-media-open');
  });

  $(".showUploadPanel").click(function(){
    $('.imageUpload-' + this.dataset.sidebarId).addClass('detail-open');
    $('.overlay').show(); // Show overlay when detail is active
    $(document.body).addClass('sidebar-media-open');
  });

})(jQuery);
</script>

@endpush

@section('page-title','Pas "' .$article->title .'" aan')

@section('content')

{!! Form::model($article,['method' => 'PUT', 'route' => ['articles.update',$article->getKey()],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
<div class="row">

  @include('back.articles._formtabs')

  @push('sidebar')
  @include('back.articles._imageupload')
  @endpush
  {{--temporary input to inject uploaded file from gallery--}}
  <input id="galleryupload" class="hidden" type="text" name="asset_id" value="">

  <div class="col-md-3">
    <article class="panel">
      <div class="panel-heading">
        Publiceer
        <div class="widget-menu pull-right">
          <a class="subtle"><i class="fa fa-eye"></i> Bekijk artikel</a>
        </div>
      </div>
      <div class="panel-body">
        <div class="bs-component">
            Url naar artikel
            <div class="well well-sm">
              <i class="fa fa-link mr5"></i>{{ url("/artikels/$article->slug") }}
            </div>
          </div>

          <div class="bs-component">
            Laatst geupdate op:
            <div class="well well-sm">
              <i class="fa fa-calendar mr5"></i> {{ $article->updated_at->format('d/m/Y H:i') }}
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-8">
              <label class="control-label" for="inputPublished">Publiceer "{{ $article->title }}"</label>
            </div>
            <div class="col-md-4">
              <div class="switch switch-success round switch-inline mt5">
                {!! Form::checkbox('published',1,$article->isPublished(),['id' => "inputPublished"]) !!}
                <label title="{{ $article->isPublished()?'Online':'Offline' }}" for="inputPublished"></label>
              </div>
            </div>
          </div>
        </div>
      <div class="panel-footer">
        <a class="subtle subtle-danger pull-left mt10" id="remove-article-toggle" href="#remove-article-modal"><i class="fa fa-remove"></i> Verwijder dit artikel?</a>
        <div class="text-right">
            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Bijwerken</button>
        </div>
      </div>
    </article>
    @stack('article-uploads')
  </div><!-- end sidebar column -->
</div>
<section class="overlay" style="display: none;"></section>
{!! Form::close() !!}
@stop

