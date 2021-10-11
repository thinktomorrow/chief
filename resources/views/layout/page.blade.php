@extends('chief::layout.master')

@push('custom-styles')
    @livewireStyles
@endpush

@push('custom-scripts')
    @livewireScripts
@endpush

@section('page-title')
    @adminConfig('pageTitle')
@endsection


@section('header')
    @isset($header)
        <div class="container">
            {!! $header !!}
        </div>
    @endisset
@endsection

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full space-y-6 lg:w-2/3">
                {!! $slot !!}
            </div>

            <div class="w-full lg:w-1/3">
                <div class="space-y-6">
                    @isset($sidebar)
                        {!! $sidebar !!}
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
