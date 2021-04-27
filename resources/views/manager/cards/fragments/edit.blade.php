<form
    id="updateForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fragment-update', $model)"
    enctype="multipart/form-data"
    role="form"
    class="mb-0"
>
    @csrf
    @method('put')

    <div class="space-y-12">
        <h3>{{ $model->adminConfig()->getPageTitle() }}</h3>

        <div data-vue-fields class="space-y-8">
            @foreach($fields as $field)
                <x-chief-formgroup label="{{ $field->getLabel() }}" isRequired="{{ $field->required() }}">
                    @if($field->getDescription())
                        <x-slot name="description">
                            <p>{{ $field->getDescription() }}</p>
                        </x-slot>
                    @endif

                    {!! $field->render(get_defined_vars()) !!}
                </x-chief-formgroup>
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

            {{-- TODO: isn't this too harsh? Maybe we can use a delete modal? --}}
            <button
                data-submit-form="removeFragment{{ $model->modelReference()->get() }}"
                class="btn btn-error-outline"
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
