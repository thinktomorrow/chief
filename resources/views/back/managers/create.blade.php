@extends('chief::back._layouts.master')

@section('page-title',$manager->modelDetails()->title)


@component('chief::back._layouts._partials.header')
    @slot('title', $manager->modelDetails()->title)
    @slot('subtitle')
        <a class="center-y" href="{{ $manager->route('index') }}"><span class="icon icon-arrow-left"></span> Terug naar alle {{ $manager->modelDetails()->plural }}</a>
    @endslot

    <div class="inline-group-s">
        <button data-submit-form="createForm" type="button" class="btn btn-primary">Aanmaken</button>
        {{--@include('chief::back.managers._partials.context-menu')--}}
    </div>

@endcomponent

@section('content')

    <div>

        <!-- needs to be before form to be detected by context-menu. Don't know why :s -->
        {{--@include('chief::back.managers._partials.delete-modal')--}}

        <form id="createForm" method="POST" action="{{ $manager->route('store') }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

             @include('chief::back.managers._partials._form')

            <div class="stack text-right">
                <button type="submit" class="btn btn-primary">Aanmaken</button>
            </div>

        </form>
    </div>

@stop

@push('custom-styles')
    <!-- make redactor available for any components. -->
    <script src="/chief-assets/back/js/vendors/redactor.js"></script>
@endpush

@push('custom-scripts-after-vue')
    @include('chief::back._layouts._partials.editor-script', ['imageUploadUrl' => $manager->route('upload')])
@endpush

@include('chief::back._elements.file-component')
@include('chief::back._elements.slimcropper-component')
@include('chief::back._elements.fileupload-component')

