 <form
    method="POST"
    action="@adminRoute('update', $model)"
    id="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
    enctype="multipart/form-data"
    role="form"
    class="mb-0"
>
    @csrf
    @method('put')

    <div class="space-y-6">
        @foreach($fields->notTagged('component') as $field)
            <x-chief-formgroup label="{{ $field->getLabel() }}" isRequired="{{ $field->required() }}">
                @if($field->getDescription())
                    <x-slot name="description">
                        <p>{{ $field->getDescription() }}</p>
                    </x-slot>
                @endif

                {!! $field->render(get_defined_vars()) !!}
            </x-chief-formgroup>
        @endforeach

        <div>
            <button
                data-submit-form="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
                type="submit"
                form="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
                class="btn btn-primary"
            >
                Wijzigingen opslaan
            </button>
        </div>
    </div>
</form>
