<form
    id="updateFieldsForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fields-update', $model, $tag)"
    enctype="multipart/form-data"
    role="form"
>
    @csrf
    @method('put')

    <div class="space-y-8">
        <h2 class="text-2xl font-bold text-grey-900">{{ ucfirst($componentTitle) }}</h2>

        <x-chief::field.multiple :tagged="$tag" />

        <button type="submit" form="updateFieldsForm{{ $model->modelReference()->get() }}" class="btn btn-primary">
            Wijzigingen opslaan
        </button>
    </div>
</form>
