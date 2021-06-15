<form
    id="updateFieldsForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fields-update', $model, $componentKey)"
    enctype="multipart/form-data"
    role="form"
    class="mb-0"
>
    @csrf
    @method('put')

    <div class="space-y-12">
        <h3>{{ ucfirst($componentKey) }}</h3>

        <div data-vue-fields class="space-y-8">
            @foreach($fields as $field)
                @include('chief::manager.cards.fields.field')
            @endforeach
        </div>

        <div>
            <button
                data-submit-form="updateFieldsForm{{ $model->modelReference()->get() }}"
                type="submit"
                form="updateFieldsForm{{ $model->modelReference()->get() }}"
                class="btn btn-primary"
            >
                Wijzigingen opslaan
            </button>
        </div>
    </div>
</form>
