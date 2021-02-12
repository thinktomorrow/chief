<form
    id="updateFieldsForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fields-update', $model, $componentKey)"
    enctype="multipart/form-data"
    role="form"
>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    <div class="space-y-16">
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

        <div>
            <button
                data-submit-form="updateFieldsForm{{ $model->modelReference()->get() }}"
                type="submit"
                form="updateFieldsForm{{ $model->modelReference()->get() }}"
                class="new-btn new-btn-primary"
            >
                Wijzigingen opslaan
            </button>
        </div>
    </div>
</form>

