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

            $('.redactor-air').redactor({
                linebreaks: true, /* linebreak instead of paragraph on enter */
                pastePlainText: true,
                maxHeight: 90,
                buttons: ['bold'],
                allowedTags: ['br','strong','b']
            });

        })(jQuery);
    </script>

@stop

@section('page-title','Add new module')

@section('content')

    {!! Form::model($module,['method' => 'POST', 'route' => ['admin.modules.store'],'files' => false,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('admin.modules._formtabs')

        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="inputService">Page</label>
                <div class="bs-component">
                    {!! Form::select('service_id',['' => '---'] + $available_services,null,['id' => 'inputService','class' => 'form-control']) !!}
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Create module</button>
                </div>
                <div class="text-center">
                    <a class="subtle" id="remove-module-toggle" href="{{ URL::previous() }}"> cancel</a>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

@stop

