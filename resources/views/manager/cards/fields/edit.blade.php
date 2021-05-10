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
                <x-chief-formgroup
                    label="{{ $field->getLabel() }}"
                    name="{{ $field->getName($locale ?? null) }}"
                    isRequired="{{ $field->required() }}"
                    data-toggle-field-target="{{ $field->getId($locale ?? null) }}"
                >
                    @if($field->getDescription())
                        <x-slot name="description">
                            <p>{{ $field->getDescription() }}</p>
                        </x-slot>
                    @endif

                    {!! $field->render(get_defined_vars()) !!}
                </x-chief-formgroup>
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
