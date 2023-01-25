@extends('chief::layout.master')

@section('page-title')
    {{ $resource->getLabel() }}
@endsection

@section('header')
    <div class="container max-w-3xl">
        <x-chief::page.header>
            @slot('title')
                {{ $resource->getLabel() }}
            @endslot

            @slot('breadcrumbs')
                @adminCan('index')
                <a href="@adminRoute('index')" class="link link-primary">
                    <x-chief-icon-label type="back">Overzicht</x-chief-icon-label>
                </a>
                @endAdminCan
            @endslot

            <button form="createForm" type="submit" class="btn btn-primary">Aanmaken</button>
        </x-chief::page.header>
    </div>
@endsection

@section('content')
    <div class="container max-w-3xl">
        <div class="row-start-start">
            <div class="w-full">

                <div class="card">
                    <form id="createForm" method="POST" action="@adminRoute('store')" enctype="multipart/form-data" role="form">
                        @csrf

                        <div class="space-y-6">
                            <x-chief-form::fields not-tagged="edit,not-on-create" />

                            <button type="submit" class="btn btn-primary">Aanmaken</button>
                        </div>
                    </form>
                </div>
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
            message.classList.add('text-sm');
            message.classList.add('italic');
            message.classList.add('opacity-30');
            message.classList.add('px-4');
            message.classList.add('py-2');
            message.innerHTML = "Het toevoegen van afbeeldingen aan de wysiwyg is pas mogelijk na het aanmaken van dit item.";
            editors[i].parentElement.appendChild(message);
        }
    </script>

    @include('chief::layout._partials.editor-script', ['disableImageUpload' => true])
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
