<div class="window window-white window-md">
    <form
        method="POST"
        action="@adminRoute('update', $model)"
        id="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
        enctype="multipart/form-data"
        role="form"
    >
        @csrf
        @method('put')

        <div class="space-y-8">
            @include('chief::manager.fields.form.fieldsets', [
                'fieldsets' => $fields->all(),
                'hasFirstWindowItem' => true,
                'hasLastWindowItem' => false,
            ])

            <button type="submit" form="updateForm{{ $model->getMorphClass().'_'.$model->id }}" class="btn btn-primary">
                Wijzigingen opslaan
            </button>
        </div>
    </form>
</div>
