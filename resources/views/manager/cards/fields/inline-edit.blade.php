 <form
    method="POST"
    action="@adminRoute('update', $model)"
    id="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
    enctype="multipart/form-data"
    role="form"
    class="mb-0"
>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    @foreach($fields->notTagged('component') as $field)
        @formgroup
            @slot('label',$field->getLabel())
            @slot('description',$field->getDescription())
            @slot('isRequired', $field->required())

            {!! $field->render(get_defined_vars()) !!}
        @endformgroup
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
</form>
