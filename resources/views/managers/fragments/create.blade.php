<div>
    <form
        id="createForm"
        method="POST"
        action="@adminRoute('fragment-store', $owner)"
        enctype="multipart/form-data"
        role="form"
        class="space-y-8"
    >
        {{ csrf_field() }}

        <div data-vue-fields class="space-y-8">
            @foreach($fields as $field)
                @formgroup
                @slot('label',$field->getLabel())
                @slot('description',$field->getDescription())
                @slot('isRequired', $field->required())
                {!! $field->render(get_defined_vars()) !!}
                @endformgroup
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Aanmaken</button>
    </form>
</div>


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

    @include('chief::back._layouts._partials.editor-script', ['disableImageUpload' => true])

@endpush

@include('chief::back._components.file-component')
@include('chief::back._components.filesupload-component')
