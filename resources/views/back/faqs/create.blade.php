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

@section('page-title','Add new faq')

@section('content')

    {!! Form::model($faq,['method' => 'POST', 'route' => ['admin.faqs.store'],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('admin.faqs._formtabs')

        <div class="col-md-3">
            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Add faq</button>
                </div>
                <div class="text-center">
                    <a class="subtle" id="remove-faq-toggle" href="{{ URL::previous() }}"> cancel</a>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

@stop

