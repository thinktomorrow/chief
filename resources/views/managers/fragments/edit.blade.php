<form
    id="updateForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fragment-update', $model)"
    enctype="multipart/form-data"
    role="form"
>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    <div class="space-y-10">
        <div data-vue-fields class="space-y-10">
            <h3>
                {{-- <span class="bg-primary-50 font-semibold text-primary-900 py-2 px-4 rounded-lg text-lg"> --}}
                    {{ ucfirst($model->managedModelKey()) }}
                {{-- </span> --}}
            </h3>

            @foreach($fields as $field)
                @formgroup
                    @slot('label',$field->getLabel())
                    @slot('description',$field->getDescription())
                    @slot('isRequired', $field->required())
                    {!! $field->render(get_defined_vars()) !!}
                @endformgroup
            @endforeach
        </div>

        @if($model instanceof \Thinktomorrow\Chief\Fragments\FragmentsOwner && $manager->can('fragments-index', $model))
            <x-chief::fragments :owner="$model"/>
        @endif

        <div>
            <button
                data-submit-form="updateForm{{ $model->modelReference()->get() }}"
                type="submit"
                form="updateForm{{ $model->modelReference()->get() }}"
                class="btn btn-primary"
            >
                Wijzigingen opslaan
            </button>
        </div>
    </div>
</form>
