@extends('chief::back._layouts.master')

@section('page-title',$manager->details()->title)


@component('chief::back._layouts._partials.header')
    @slot('title', $manager->details()->title)
    @slot('subtitle')
        <div class="inline-block">
            <a class="center-y" href="{{ $manager->route('index') }}">
                <svg width="18" height="18" class="mr-2"><use xlink:href="#arrow-left"/></svg>
                Terug naar alle {{ $manager->details()->plural }}
            </a>
        </div>
    @endslot

    <div class="inline-group-s flex items-center">

        @if($manager->isAssistedBy('publish'))
            {!! $manager->assistant('publish')->publicationStatusAsLabel() !!}
        @endif

        @if($manager->can('update'))
            <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
            @include('chief::back.managers._partials.context-menu')
        @endif
    </div>

@endcomponent

@section('content')
    <div>

        <!-- needs to be before form to be detected by context-menu. Don't know why :s -->
        @include('chief::back.managers._partials.delete-modal')
        <form id="updateForm" method="POST" action="{{ $manager->route('update') }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <input type="hidden" name="_method" value="PUT">

                @include('chief::back.managers._partials._form', [
                    'fieldArrangement' => $manager->fieldArrangement('edit')
                ])

            @if($manager->can('update'))
                <div class="stack text-right">
                    <button type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
                </div>
            @endif

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
