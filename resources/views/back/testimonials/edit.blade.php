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
                toolbarFixed: true, /* stick op top on scroll */
                imageUpload: '{{ route('admin.testimonials.contactimages.upload') }}?id={{ $testimonial->id }}&_token={{ csrf_token() }}',
                image_dir: '{{ $testimonial::getContentImageDirectory() }}',
                imageUploadErrorCallback: function(json)
                {
                    $('body').prepend('<div class="alert alert-top alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><div class="container">'+json.message+'</div></div>');
                }
            });

            // Delete modal
            $("#remove-testimonial-toggle").magnificPopup();

            // Sortable
            var el = document.getElementsByClassName('sortable')[0];
            var sortable = Sortable.create(el);

        })(jQuery);
    </script>

@stop

@section('page-title','Testimonial van '.$testimonial->name)

@section('topbar-right')
        <a type="button" href="{{ route('testimonials.show',$testimonial->slug) }}" target="_blank" class="btn btn-default btn-sm btn-rounded"><i class="fa fa-eye"></i> View on site</a>
@stop

@section('content')

    {!! Form::model($testimonial,['method' => 'PUT', 'route' => ['admin.testimonials.update',$testimonial->id],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        <div class="col-md-9">
            <div class="panel mb25">
                <div class="panel-body">
                    @include('admin.testimonials._form')
                </div>
            </div>
        </div><!-- end first column -->

        <div class="col-md-3">
            @include('admin.testimonials._editsidebar')
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

    @include('admin.testimonials._deletemodal')

@stop

