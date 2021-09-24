<form
    id="updateFieldsForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fields-update', $model, $componentKey)"
    enctype="multipart/form-data"
    role="form"
>
    @csrf
    @method('put')

    <div data-vue-fields class="space-y-8">
        <h2 class="text-2xl font-bold text-grey-900">{{ ucfirst($componentTitle) }}</h2>

        @include('chief::manager.fields.form.fieldsets', [
            'fieldsets' => $fields->all(),
            'hasFirstWindowItem' => false,
            'hasLastWindowItem' => false,
        ])

        <button type="submit" form="updateFieldsForm{{ $model->modelReference()->get() }}" class="btn btn-primary">
            Wijzigingen opslaan
        </button>
    </div>
</form>
