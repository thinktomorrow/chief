@extends('chief::layout.master')

@section('page-title')
    @isset($pageTitle)
        {{ $pageTitle }}
    @else
        {{ $resource->getPageTitle($model) }}
    @endisset
@endsection

@section('header')
    @isset($header)
        <div class="container">
            @isset($breadcrumbs)
                {!! $breadcrumbs !!}
                {!! $header !!}
            @else
                <div class="space-y-2">
                    <x-chief::page.breadcrumbs />
                    {!! $header !!}
                </div>
            @endisset
        </div>
    @endisset
@endsection

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full lg:w-2/3">
                <div class="space-y-6">
                    {!! $slot !!}
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="space-y-6">
                    @isset($aside)
                        {!! $aside !!}
                    @endisset
                </div>
            </div>
        </div>
    </div>
@stop

@push('portals')
    @adminCan('delete', $model)
        @include('chief::manager._transitions.modals.delete-modal')
    @endAdminCan

    @adminCan('archive', $model)
        @include('chief::manager._transitions.modals.archive-modal')
    @endAdminCan
@endpush

@push('custom-scripts-after-vue')
    @include('chief::layout._partials.editor-script')
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
