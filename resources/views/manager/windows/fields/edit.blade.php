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
        <p class="text-2xl display-base display-dark">{{ ucfirst($componentTitle) }}</p>

        <div class="space-y-8">
            <x-chief::field.multiple :tagged="$tag" />
        </div>

        <button type="submit" form="updateFieldsForm{{ $model->modelReference()->get() }}" class="btn btn-primary">
            Wijzigingen opslaan
        </button>
    </div>
</form>
