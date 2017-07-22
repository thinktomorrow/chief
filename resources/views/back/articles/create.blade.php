@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@section('topbar-right')
    <button type="button" class="btn btn-default mr5" id="showUploadPanel">
        <span class="fa fa-upload"></span>
        Upload nieuw bestand
    </button>
@stop


@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/back/vendor/redactor/redactor.css') }}">
@stop

@push('custom-scripts')
    {{--<script src="{{ asset('assets/back/vendor/redactor/redactor.js') }}"></script>--}}
    <script>
        ;(function ($) {

//            $('.redactor-editor').redactor({
//                focus: true,
//                pastePlainText: true,
//                buttons: ['html', 'formatting', 'bold', 'italic',
//                    'unorderedlist', 'orderedlist', 'outdent', 'indent',
//                    'link', 'alignment','horizontalrule']
//            });

            // Initiate our cropper
//            new Cropper();

	        $("#showUploadPanel").click(function(){
		        $(document.body).toggleClass('upload-open');
	        });
        })(jQuery);

    </script>

@endpush

@push('sidebar')
    @include('back.articles._fileupload')
@endpush

@section('content')

    {!! Form::model($article,['method' => 'POST', 'route' => ['articles.store'],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('back.articles._formtabs')

        <aside class=" col-md-3">
          <div class="panel">
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
                    <i class="fa fa-link mr5"></i>{{ url('/articles')}}
                  </div>
                </div>
                <div class="input-group">
                  Status
                  <select>
                    <option>Gepubliceerd</option>
                    <option>Concept</option>
                  </select>
                </div>
              </div>
              <div class="panel-footer">
                <a class="subtle pull-left mt10" id="remove-article-toggle" href="{{ URL::previous() }}"><i class="fa fa-long-arrow-left"></i> Terug</a>
                <div class="text-right">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Publiceer artikel</button>
                </div>
              </div>
            </div>

            <div class="panel image">
              <div class="panel-heading">Media toevoegen</div>
              <div class="panel-body">
                <a href="#" class="btn text-primary">Voeg afbeelding toe</a>
              </div>
            </div>
            <div class="panel">
              <div class="panel-heading">Bijlages toevoegen</div>
              <div class="panel-body">
                <a href="#" class="btn text-primary">Voeg bijlage toe</a>
              </div>
            </div>
        </aside><!-- end sidebar column -->
    </div>

    {!! Form::close() !!}

@stop

