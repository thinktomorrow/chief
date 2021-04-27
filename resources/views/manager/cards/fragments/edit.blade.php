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

    <div class="space-y-8">
        <h3>{{ $model->adminConfig()->getPageTitle() }}</h3>

        <div data-vue-fields class="space-y-6">
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

        <h4>Delen</h4>

        <ul>
        @foreach(app(Thinktomorrow\Chief\Fragments\Actions\GetOwningModels::class)->get($model->fragmentModel()) as $otherOwner)
            @if($otherOwner['model']->modelReference()->equals($owner->modelReference())) @continue @endif
                <li>wordt ook gebruikt door <a class="underline" href="{{ $otherOwner['manager']->route('edit', $otherOwner['model']) }}">{{ $otherOwner['model']->adminConfig()->getPageTitle() }}</a></li>
        @endforeach
        </ul>

    @if($model->fragmentModel()->isShared())
            <p>Dit blok wordt gedeeld en kan worden geselecteerd door alle pagina's. Het niet langer deelbaar maken heeft enkel effect voor nieuwe pagina's. Voor de huidige pagina's blijft dit blok gedeeld.</p>
            <button
                    data-submit-form="unshareFragment{{ $model->modelReference()->get() }}"
                    class="btn btn-info-outline"
                    type="submit"
                    form="unshareFragment{{ $model->modelReference()->get() }}"
            >
                Niet langer deelbaar maken
            </button>
        @else
            <p>Maak dit blok selecteerbaar op alle pagina's. Aanpassingen aan de inhoud worden op alle pagina's toegepast.</p>
            <button
                    data-submit-form="shareFragment{{ $model->modelReference()->get() }}"
                    class="btn btn-info-outline"
                    type="submit"
                    form="shareFragment{{ $model->modelReference()->get() }}"
            >
                Deelbaar maken
            </button>
        @endif

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

<form
        id="shareFragment{{ $model->modelReference()->get() }}"
        method="POST"
        action="{{ $manager->route('fragment-share', $model) }}"
>
    @csrf
</form>

<form
        id="unshareFragment{{ $model->modelReference()->get() }}"
        method="POST"
        action="{{ $manager->route('fragment-unshare', $model) }}"
>
    @csrf
</form>
