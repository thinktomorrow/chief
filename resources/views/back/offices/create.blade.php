@extends('admin._layouts.master')

@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/redactor/redactor.css') }}">
@stop

@section('custom-scripts')
    <script src="{{ asset('assets/admin/vendor/redactor/redactor.js') }}"></script>
    <script>
        ;(function ($) {

            $('.redactor-editor').redactor({
                focus: true,
                pastePlainText: true,
                buttons: ['html','formatting','bold','italic',
                    'unorderedlist','orderedlist','outdent','indent',
                    'link','alignment']
            });

        })(jQuery);
    </script>

@stop

@section('page-title','Add new office')

@section('content')

    {!! Form::model($office,['method' => 'POST', 'route' => ['admin.offices.store'],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        <div class="col-md-9">
            <div class="panel mb25">
                <div class="panel-body">
                    @include('admin.offices._form')
                </div>
            </div>
        </div><!-- end first column -->

        <div class="col-md-3">
            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Add office</button>
                </div>
                <div class="text-center">
                    <a class="subtle" id="remove-office-toggle" href="{{ URL::previous() }}"> cancel</a>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

@stop

