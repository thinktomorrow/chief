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

            // Delete modal
            $("#remove-service-toggle").magnificPopup();

            // Sortable
            var el = document.getElementsByClassName('sortable')[0];
            var sortable = Sortable.create(el);

        })(jQuery);
    </script>

@stop

@section('page-title','Factoring page: '.$service->title)

@section('topbar-right')
        <a type="button" href="{{ route('services.show',$service->slug) }}" target="_blank" class="btn btn-default btn-sm btn-rounded"><i class="fa fa-eye"></i> View on site</a>
@stop

@section('content')

    {!! Form::model($service,['method' => 'PUT', 'route' => ['admin.services.update',$service->id],'files' => false,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('admin.services._formtabs')

        <div class="col-md-3">

            <div class="form-group">
                <label class="control-label" for="inputService">Position in site navigation:</label>
                <i class="fa fa-question-circle" data-toggle="tooltip" title="Drag and drop your page to its new position."></i>
                <div class="bs-component">
                    <ul class="list-group sortable">

                        @foreach(\BNP\Services\Service::getAll() as $sibling)

                            <?php $current = ($sibling->id === $service->id) ? ' current' : null; ?>

                            <li class="list-group-item{{$current}}">
                                <input type="hidden" name="sequence[]" value="{{ $sibling->id }}">
                                <span title="{{ $sibling->title }}">{{ teaser($sibling->title,36,'...') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Save your changes</button>
                </div>
                <div class="text-center">
                    <a class="subtle subtle-danger" id="remove-service-toggle" href="#remove-service-modal"><i class="fa fa-remove"></i> remove this page?</a>
                </div>

            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

    @include('admin.services._deletemodal')

@stop

