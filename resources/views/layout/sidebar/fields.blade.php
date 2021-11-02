<form
        id="updateFieldsForm{{ $model->modelReference()->get() }}"
        method="POST"
        action="@adminRoute('fields-update', $model, $tag)"
        enctype="multipart/form-data"
        role="form"
>
    @csrf
    @method('put')

    <div class="space-y-8 mt-16">

        {!! $slot !!}

        <button type="submit" form="updateFieldsForm{{ $model->modelReference()->get() }}" class="btn btn-primary">
            Wijzigingen opslaan
        </button>
    </div>
</form>
