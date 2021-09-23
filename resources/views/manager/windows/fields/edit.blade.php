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

        <hr class="-window-x text-grey-100">

        @foreach($fields->all() as $i => $fieldSet)
            @include('chief::manager.fields.form.fieldset', ['index' => $i])

            <hr class="-window-x text-grey-100">
        @endforeach

        <button
            type="submit"
            form="updateFieldsForm{{ $model->modelReference()->get() }}"
            class="btn btn-primary"
        >
            Wijzigingen opslaan
        </button>
    </div>
</form>
