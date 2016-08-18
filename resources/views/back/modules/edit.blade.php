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

            // Delete modal
            $("#remove-module-toggle").magnificPopup();

            // Sortable
            var el = document.getElementsByClassName('sortable')[0];
            var sortable = Sortable.create(el);

        })(jQuery);
    </script>

@stop

@section('page-title','Module: '.$module->strippedTitle)

@section('topbar-right')
        <a type="button" href="{{ route('modules.show',$module->slug) }}" target="_blank" class="btn btn-default btn-sm btn-rounded"><i class="fa fa-eye"></i> View on site</a>
@stop

@section('content')

    {!! Form::model($module,['method' => 'PUT', 'route' => ['admin.modules.update',$module->id],'files' => false,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('admin.modules._formtabs')

        <div class="col-md-3">

            <div class="form-group">
                <label class="control-label" for="inputService">Show on page:</label>
                <div class="bs-component">
                    {!! Form::select('service_id',['' => '---'] + $available_services,null,['id' => 'inputService','class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="inputService">Position on the page:</label>
                <i class="fa fa-question-circle" data-toggle="tooltip" title="Drag and drop your module to its new position."></i>
                <span class="subtle">Order your modules by dragging them to their new position.</span>
                <div class="bs-component">
                    <ul class="list-group sortable">

                        @foreach($module->getSiblings() as $sibling)

                            <?php $current = ($sibling->id === $module->id) ? ' current' : null; ?>

                            <li class="list-group-item{{$current}}">
                                <input type="hidden" name="sequence[]" value="{{ $sibling->id }}">
                                <span title="{{ $sibling->strippedTitle }}">{{ teaser($sibling->strippedTitle,36,'...') }}</span>
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
                    <a class="subtle subtle-danger" id="remove-module-toggle" href="#remove-module-modal"><i class="fa fa-remove"></i> remove this module?</a>
                </div>

            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

    @include('admin.modules._deletemodal')

@stop

