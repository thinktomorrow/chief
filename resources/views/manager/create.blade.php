@extends('chief::layout.master')

@section('page-title')
    @adminConfig('modelName')
@endsection

@section('header')
    <div class="container-sm">
        <x-chief::page.header>
            @slot('title')
                @adminConfig('modelName')
            @endslot

            @slot('breadcrumbs')
                @adminCan('index')
                <a href="@adminRoute('index')" class="link link-primary">
                    <x-chief-icon-label type="back">Terug naar overzicht</x-chief-icon-label>
                </a>
                @endAdminCan
            @endslot

            <button form="createForm" type="submit" class="btn btn-primary">Aanmaken</button>
        </x-chief::page.header>
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <x-chief::window>
                    <form id="createForm" method="POST" action="@adminRoute('store')" enctype="multipart/form-data" role="form">
                        @csrf

                        <div class="space-y-6">
                            <x-chief::field.multiple not-tagged="edit,not-on-create" />

                            <button type="submit" class="btn btn-primary">Aanmaken</button>
                        </div>
                    </form>
                </x-chief::window>
            </div>
        </div>
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
