@php
    $formId = 'createForm' . \Illuminate\Support\Str::random(8);
@endphp

<form
    id="{{ $formId }}"
    method="POST"
    action="@adminRoute('fragment-store', $owner)"
    enctype="multipart/form-data"
    role="form"
>
    @csrf

    <input type="number" name="order" value="{{ $order ?? 0 }}" hidden>

    <div class="space-y-8">
        <h3>{{ ucfirst($model->adminConfig()->getModelName()) }}</h3>

        <x-chief::fields.form />

        <button form="{{ $formId }}" type="submit" class="btn btn-primary">
            Aanmaken
        </button>
    </div>
</form>

@push('custom-scripts-after-vue')
    {{-- TODO: Do we still need this? Images aren't typically available in redactor anymore. --}}
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
