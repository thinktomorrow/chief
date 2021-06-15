<form
    id="createForm"
    method="POST"
    action="@adminRoute('fragment-store', $owner)"
    enctype="multipart/form-data"
    role="form"
>
    @csrf

    <input type="number" name="order" value="0" hidden>

    <div class="space-y-12">
        <h3>{{ ucfirst($model->adminConfig()->getPageTitle()) }}</h3>

        <div data-vue-fields class="space-y-8">
            @foreach($fields as $field)
                @include('chief::manager.cards.fields.field')
            @endforeach
        </div>

        <div>
            <button form="createForm" type="submit" class="btn btn-primary">
                Aanmaken
            </button>
        </div>
    </div>
</form>


@push('custom-scripts-after-vue')
    {{-- TODO: we need a better implementation for this --}}
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
