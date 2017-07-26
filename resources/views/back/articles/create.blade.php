@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@section('topbar-right')
    <button type="button" class="btn btn-default btn-rounded btn-sm mt10" id="showUploadPanel">
        <span class="fa fa-upload"></span>
        Upload nieuw bestand
    </button>
@stop


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
                    'link', 'alignment','horizontalrule']
            });

            // Initiate our cropper
//            new Cropper();

	        $("#showUploadPanel").click(function(){
		        $(document.body).toggleClass('upload-open');
	        });
	        $('#datetimepicker').datetimepicker({
              format: "DD-MM-YYYY",
              pickTime: false,
              pick12HourFormat: true
          });
        })(jQuery);

    </script>

@endpush

@section('content')

    {!! Form::model($article,['method' => 'POST', 'route' => ['articles.store'],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        <div class="col-md-6">

        </div>

        @include('back.articles.form.formtabs')

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
                <div class="bs-component">
                  <label for="datetimepicker">Datum</label>
                  <div class="input-group date" id="datetimepicker">
                      <span class="input-group-addon cursor">
                          <i class="fa fa-calendar"></i>
                      </span>
                      <input type="text" name="publication" class="form-control">
                  </div>
                </div>
              </div>
              <div class="panel-footer">
                <a class="subtle pull-left mt10" id="remove-article-toggle" href="{{ URL::previous() }}"><i class="fa fa-long-arrow-left"></i> Terug</a>
                <div class="text-right">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Publiceer artikel</button>
                </div>
              </div>
        </aside><!-- end sidebar column -->
    </div>

    {!! Form::close() !!}

@stop

