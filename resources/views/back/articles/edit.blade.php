@extends('back._layouts.master')

@push('custom-styles')
<link rel="stylesheet" href="{{ asset('assets/back/vendor/redactor2/redactor.css') }}">
<link href="{{ asset('assets/back/theme/vendor/plugins/datepicker/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/back/vendor/redactor2/redactor.js') }}"></script>
<script src="{{ asset('assets/back/theme/js/utility/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/back/theme/vendor/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/back/theme/vendor/plugins/datepicker/js/bootstrap-datetimepicker.js') }}"></script>
<script>
;(function ($) {

  $('.redactor-editor').redactor({
    focus: true,
    pastePlainText: true,
    buttons: ['html', 'formatting', 'bold', 'italic',
    'unorderedlist', 'orderedlist', 'outdent', 'indent',
    'link', 'alignment','image','horizontalrule'],
    {{--imageUpload: '{{ route('article.upload', $article->id) }}&_token={{ csrf_token() }}',--}}
    {{--image_dir: '{{ $article::getContentImageDirectory() }}',--}}
    {{--imageUploadErrorCallback: function(json)--}}
    {{--{--}}
      {{--$('body').prepend('<div class="alert alert-top alert-danger alert-dismissable"><button type="button" class="cl data-dismiss="alert" aria-hidden="true">&times;</button><div class="container">'+json.message+'</div></div>');--}}
    {{--}--}}
  });

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
    $('.overlay').hide(); // Hide overlay when detail is active
    $(document.body).removeClass('sidebar-media-open');
  });

  $(".showUploadPanel").click(function(){
    $('.imageUpload-' + this.dataset.sidebarId).addClass('detail-open');
    $('.overlay').show(); // Show overlay when detail is active
    $(document.body).addClass('sidebar-media-open');
  });

  $('#datetimepicker').datetimepicker({
      format: "DD-MM-YYYY",
      pickTime: false,
      pick12HourFormat: true
  });

})(jQuery);
</script>

@endpush

@section('page-title','Pas "' .$article->title .'" aan')

@section('content')

{!! Form::model($article,['method' => 'PUT', 'route' => ['back.articles.update',$article->getKey()],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
<div class="row">

  @include('back.articles.form.formtabs')


  {{--temporary input to inject uploaded file from gallery--}}

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
            <label for="datetimepicker">Gepubliceerd op</label>
            <div class="input-group date mb20" id="datetimepicker">
                <span class="input-group-addon cursor">
                    <i class="fa fa-calendar"></i>
                </span>
                <input type="text" name="publication" class="form-control" value="{{ $article->created_at->format('d/m/Y') }}">
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
    <div class="bs-component text-center text-muted subtle">
      Laatst veranderd op: {{ $article->updated_at->format('d/m/Y H:i') }}
    </div>
    @stack('article-uploads')
  </div><!-- end sidebar column -->
</div>
<section class="overlay" style="display: none;"></section>
{!! Form::close() !!}
@stop

