@extends('chief::layout.master')

@section('page-title')
    @adminConfig('modelName')
@endsection

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title')
                @adminConfig('modelName')
            @endslot

            @slot('breadcrumbs')
                @adminCan('index')
                    <a href="@adminRoute('index')" class="link link-primary">
                        <x-icon-label type="back">Terug naar overzicht</x-icon-label>
                    </a>
                @endAdminCan
            @endslot

            <button form="createForm" type="submit" class="btn btn-primary">Aanmaken</button>
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full">
                <div class="window window-white">
                    <form
                        id="createForm"
                        method="POST"
                        action="@adminRoute('store')"
                        enctype="multipart/form-data"
                        role="form"
                        class="mb-0"
                    >
                        @csrf

                        <div class="space-y-8">
                            @foreach($fields as $field)
                                @include('chief::manager.cards.fields.field')
                            @endforeach

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
            message.style = "font-style: italic; opacity: 0.3; margin-top: 1rem;";
            message.innerHTML = "Het toevoegen van afbeeldingen aan de wysiwyg is pas mogelijk na het aanmaken van dit item.";
            editors[i].parentElement.appendChild(message);
        }
    </script>

    @include('chief::layout._partials.editor-script', ['disableImageUpload' => true])
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
