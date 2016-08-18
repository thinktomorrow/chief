@extends('admin._layouts.master')

@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/redactor/redactor.css') }}">
@stop

@section('custom-scripts')
    <script src="{{ asset('assets/admin/vendor/redactor/redactor.js') }}"></script>
    <script>

        ;(function ($) {

            $('.redactor-editor').redactor({
                pastePlainText: true,
                buttons: ['html','formatting','bold','italic',
                    'unorderedlist','orderedlist','outdent','indent',
                    'link','alignment','image','horizontalrule']
            });

        })(jQuery);
    </script>

@stop

@section('page-title','Translations for '.$group->label)

@section('topbar-right')
    @if(Auth::user()->isSuperAdmin())
        <a type="button" href="{{ route('admin.trans.lines.create',$group->slug) }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> add new line</a>
    @endif
@stop

@section('content')

    {!! Form::open(['method' => 'PUT', 'route' => ['admin.trans.update',$group->id],'files' => false,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('admin.trans._formtabs')

        <div class="col-md-3">
            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Save your changes</button>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}


@stop

