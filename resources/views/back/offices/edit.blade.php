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
                    'link','alignment','horizontalrule'],
                toolbarFixed: true /* stick op top on scroll */
            });

            // Delete modal
            $("#remove-office-toggle").magnificPopup();

            // Sortable
            var el = document.getElementsByClassName('sortable')[0];
            var sortable = Sortable.create(el);

        })(jQuery);
    </script>

@stop

@section('page-title','Office van '.$office->title)

@section('topbar-right')
    <a type="button" href="{{ route('pages.home') }}" target="_blank" class="btn btn-default btn-sm btn-rounded"><i class="fa fa-eye"></i> View on site</a>
@stop

@section('content')

    {!! Form::model($office,['method' => 'PUT', 'route' => ['admin.offices.update',$office->id],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        <div class="col-md-9">
            <div class="panel mb25">
                <div class="panel-body">
                    @include('admin.offices._form')
                </div>
            </div>
        </div><!-- end first column -->

        <div class="col-md-3">
            @include('admin.offices._editsidebar')
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

    @include('admin.offices._deletemodal')

@stop

