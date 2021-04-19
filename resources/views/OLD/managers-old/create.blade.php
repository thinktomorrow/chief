@extends('chief::layout.master')

@section('page-title',$manager->details()->title)

@component('chief::layout._partials.header')
    @slot('title', $manager->details()->title)
    @slot('subtitle')
        <div class="inline-block">
            <a class="center-y" href="{{ $manager->route('index') }}">
                <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
            </a>
        </div>
    @endslot

    <div class="inline-group-s">
        <button data-submit-form="createForm" type="button" class="btn btn-primary">Aanmaken</button>
    </div>

@endcomponent

@section('content')

    <div>
        <form id="createForm" method="POST" action="{{ $manager->route('store') }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            @include($manager->createView(), array_merge([
                'fields' => $manager->createFields(),
            ], $manager->createViewData()))

            @include('chief::back.managers._partials._templatefield')

            <div class="stack text-right">
                <button type="submit" class="btn btn-primary">Aanmaken</button>
            </div>

        </form>
    </div>

@stop



@push('custom-scripts-after-vue')

    <script>
        // Display a warning message to tell the user that adding images to redactor is only possible after page creation.
        var editors = document.querySelectorAll('[data-editor]');
        for(var i = 0; i < editors.length; i++) {
            var message = document.createElement('p');
            message.style = "font-style: italic; opacity: 0.3; margin-top: 1rem;";
            message.innerHTML = "Het toevoegen van afbeeldingen aan de wysiwyg is pas mogelijk na het aanmaken van dit item.";
            editors[i].parentElement.appendChild(message);
        }
    </script>

    @include('chief::layout._partials.editor-script', ['disableImageUpload' => true])

@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
