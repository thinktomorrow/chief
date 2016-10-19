@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')


@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/back/vendor/redactor/redactor.css') }}">
@stop

@push('custom-scripts')
    <script src="{{ asset('assets/back/vendor/redactor/redactor.js') }}"></script>
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
            new Cropper();

        })(jQuery);
    </script>

@endpush

@section('content')

    {!! Form::model($article,['method' => 'POST', 'route' => ['back.articles.store'],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
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

            <hr class="xsmall">

            <div class="form-group">

                @include('back.articles._fileupload')

            </div>

        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

@stop

