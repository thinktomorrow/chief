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
        @foreach($fields->notTagged('component')->all() as $i => $fieldSet)
            @include('chief::manager.fields.form.fieldset')
        @endforeach

        <div>
            <button
                type="submit"
                form="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
                class="btn btn-primary"
            >
                Wijzigingen opslaan
            </button>
        </div>
    </div>
</form>
