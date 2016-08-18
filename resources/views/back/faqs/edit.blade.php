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
                    'link','alignment','image','horizontalrule'],
                toolbarFixed: true /* stick op top on scroll */
            });

            // Delete modal
            $("#remove-faq-toggle").magnificPopup();

            // Sortable
            var el = document.getElementsByClassName('sortable')[0];
            var sortable = Sortable.create(el);

        })(jQuery);
    </script>

@stop

@section('page-title','Faq '.$faq->title)

@section('topbar-right')
        <a type="button" href="{{ route('pages.faq') }}" target="_blank" class="btn btn-default btn-sm btn-rounded"><i class="fa fa-eye"></i> View on site</a>
@stop

@section('content')

    {!! Form::model($faq,['method' => 'PUT', 'route' => ['admin.faqs.update',$faq->id],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('admin.faqs._formtabs')

        <div class="col-md-3">
            @include('admin.faqs._editsidebar')
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

    @include('admin.faqs._deletemodal')

@stop

