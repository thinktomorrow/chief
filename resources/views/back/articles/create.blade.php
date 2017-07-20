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

@section('sidebar')
    @include('back.articles._fileupload')
@stop

@section('content')

    {!! Form::model($article,['method' => 'POST', 'route' => ['articles.store'],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('back.articles._formtabs')

        <div class="col-md-3">

            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Creeër artikel</button>
                </div>
                <div class="text-center">
                    <a class="subtle" id="remove-article-toggle" href="{{ URL::previous() }}"> terug</a>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>

    {!! Form::close() !!}

@stop

