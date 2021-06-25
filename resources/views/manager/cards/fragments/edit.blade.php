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
        <h3>{{ ucfirst($model->adminConfig()->getModelName()) }}</h3>

        <div data-vue-fields class="space-y-8">
            @foreach($fields as $field)
                @include('chief::manager.cards.fields.field')
            @endforeach
        </div>

        @if($model instanceof \Thinktomorrow\Chief\Fragments\FragmentsOwner && $manager->can('fragments-index', $model))
            <x-chief::fragments :owner="$model"/>
        @endif

        <div class="flex space-x-4">
            <button
                data-submit-form="updateForm{{ $model->modelReference()->get() }}"
                type="submit"
                form="updateForm{{ $model->modelReference()->get() }}"
                class="btn btn-primary"
            >
                Wijzigingen opslaan
            </button>

            <div data-vue-fields>
                @adminCan('fragment-delete', $model)
                    <a v-cloak @click="showModal('delete-fragment-{{ str_replace('\\','',$model->modelReference()->get()) }}')" class="cursor-pointer btn btn-error-outline">
                        @if($model->fragmentModel()->isShared())
                            Verwijder op deze pagina
                        @else
                            Verwijder
                        @endif

                    </a>
                @endAdminCan
            </div>
        </div>

    </div>
</form>

@if($model->fragmentModel()->isShared())
    <div class="bg-orange-50 p-4 p-4 text-sm text-grey-700 mb-6 mt-8">
        <span class="text-orange-500 font-bold mb-4">Gedeeld fragment</span>
        <p class="text-sm">
            Aanpassingen zijn van toepassing op alle pagina's. Dit gedeelde fragment komt ook voor op:

            @foreach(app(Thinktomorrow\Chief\Fragments\Actions\GetOwningModels::class)->get($model->fragmentModel()) as $otherOwner)
                @if($otherOwner['model']->modelReference()->equals($owner->modelReference())) @continue @endif
                <a
                        class="underline link link-primary"
                        href="{{ $otherOwner['manager']->route('edit', $otherOwner['model']) }}"
                >
                    {{ $otherOwner['model']->adminConfig()->getPageTitle() }}
                </a>@if(!$loop->last), @endif
            @endforeach
        </p>

        <button
                class="btn mt-4 text-sm"
                type="submit"
                form="detachSharedFragment{{ $model->modelReference()->get() }}"
        >
            Bewerk voortaan als apart fragment op deze pagina
        </button>
    </div>

@else
    <div class="bg-blue-50 p-4 p-4 text-sm text-grey-700 mb-6 mt-8">
        <button
                data-submit-form="copyFragment{{ $model->modelReference()->get() }}"
                class="btn"
                type="submit"
                form="copyFragment{{ $model->modelReference()->get() }}"
        >
            Kopieer op deze pagina
        </button>
    </div>
@endif

<div data-vue-fields>
    @include('chief::manager._transitions.modals.delete-fragment-modal')
</div>

<form
        id="copyFragment{{ $model->modelReference()->get() }}"
        method="POST"
        action="{{ $manager->route('fragment-copy', $owner, $model) }}"
>
    @csrf
</form>

<form
        id="detachSharedFragment{{ $model->modelReference()->get() }}"
        method="POST"
        action="{{ $manager->route('fragment-detach-shared', $owner, $model) }}"
>
    @csrf
</form>
