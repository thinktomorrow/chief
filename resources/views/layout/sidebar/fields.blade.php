<form
    id="updateFieldsForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fields-update', $model, $tag)"
    enctype="multipart/form-data"
    role="form"
>
    @csrf
    @method('put')

    <div class="space-y-12">
        <div class="space-y-2">
            {{-- TODO(ben): should be the field window title --}}
            <p class="text-2xl display-base display-dark">
                Bewerken
            </p>
        </div>

        <div class="space-y-6">
            {!! $slot !!}

            <button type="submit" form="updateFieldsForm{{ $model->modelReference()->get() }}" class="btn btn-primary">
                Wijzigingen opslaan
            </button>
        </div>
    </div>
</form>
