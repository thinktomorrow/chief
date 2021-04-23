<form
    id="updateForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fragment-update', $model)"
    enctype="multipart/form-data"
    role="form"
>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    <div class="space-y-12">
        <h3>{{ $model->adminConfig()->getPageTitle() }}</h3>

        <div data-vue-fields class="space-y-10">
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

        <div class="space-x-4">
            <button
                data-submit-form="updateForm{{ $model->modelReference()->get() }}"
                type="submit"
                form="updateForm{{ $model->modelReference()->get() }}"
                class="btn btn-primary"
            >
                Wijzigingen opslaan
            </button>

            {{-- TODO: too harsh?  --}}
            <button
                data-submit-form="removeFragment{{ $model->modelReference()->get() }}"
                class="btn btn-error"
                type="submit"
                form="removeFragment{{ $model->modelReference()->get() }}"
            >
                Verwijderen
            </button>
        </div>
    </div>
</form>

<form
    id="removeFragment{{ $model->modelReference()->get() }}"
    method="POST"
    action="{{ $manager->route('fragment-remove', $owner, $model) }}"
>
    @csrf
    @method('delete')
</form>
