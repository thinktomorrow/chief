@extends('chief::back._layouts.master')

@section('page-title','Pas "' .$page->title .'" aan')


@component('chief::back._layouts._partials.header')
    @slot('title', $page->title)
    @slot('subtitle')
        <a class="center-y" href="{{ route('chief.back.pages.index',$page->collectionKey()) }}"><span class="icon icon-arrow-left"></span> Terug naar alle {{ $page->collectionDetails()->plural }}</a>
    @endslot

    <div class="inline-group-s">
        {!! $page->statusAsLabel() !!}

        <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
        @include('chief::back.pages._partials.context-menu')
    </div>

@endcomponent

@section('content')

    <div v-cloak class="v-loader inset-xl text-center">loading...</div>
    <div v-cloak>

        <!-- needs to be before form to be detected by context-menu. Don't know why :s -->
        @include('chief::back.pages._partials.delete-modal')

        <form id="updateForm" method="POST" action="{{ route('chief.back.pages.update', $page->id) }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <input type="hidden" name="_method" value="PUT">

            @include('chief::back.pages._form')

        </form>

        @foreach($page->modules as $module)
            @include('chief::back.modules._partials.delete-modal', [
                'module' => $module
            ])
        @endforeach
    </div>

@stop

@push('custom-styles')
    <!-- make redactor available for any components. -->
    <script src="/chief-assets/back/js/vendors/redactor.js"></script>
@endpush

@push('custom-scripts-after-vue')
    @include('chief::back._layouts._partials.editor-script', ['imageUploadUrl' => route('pages.media.upload', $page->id)])
@endpush

@include('chief::back._elements.file-component')
@include('chief::back._elements.slimcropper-component')
@include('chief::back._elements.fileupload-component')

