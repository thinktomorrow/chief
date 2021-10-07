<form
    id="updateForm{{ $model->modelReference()->get() }}"
    method="POST"
    action="@adminRoute('fragment-update', $model)"
    enctype="multipart/form-data"
    role="form"
>
    @csrf
    @method('put')

    <div class="space-y-8">
        <div class="space-y-2">
            <h3>{{ ucfirst($model->adminConfig()->getModelName()) }}</h3>

            <div class="flex items-center group">
                {{-- bookmark for this fragment --}}
                @if($model instanceof \Thinktomorrow\Chief\Fragments\Assistants\HasBookmark)
                    <span class="mr-2 label label-grey-light">#{{ $model->getBookmark() }}</span>

                    <div class="inline-flex items-center gutter-1">
                        @if($owner instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
                            <span class="transform scale-0 group-hover:scale-100 transition-150">
                                <a
                                    href="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                                    target="_blank"
                                    class="link link-primary -mt-0.5"
                                >
                                    <x-chief-icon-label icon="icon-external-link" size="18"></x-chief-icon-label>
                                </a>
                            </span>
                        @endif

                        <span class="transform scale-0 group-hover:scale-100 transition-150">
                            <span
                                data-copy-to-clipboard="bookmark"
                                data-copy-value="{{ $owner->url() }}#{{ $model->getBookmark() }}"
                                data-copy-success-content="Gekopiëerd!"
                                title="copy link"
                                class="leading-none cursor-pointer link link-primary"
                            >
                                <x-chief-icon-label icon="icon-link" size="18"></x-chief-icon-label>
                            </span>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        @include('chief::manager.fields.form.fieldsets', [
            'fieldsets' => $fields->all(),
            'hasFirstWindowItem' => false,
            'hasLastWindowItem' => false,
        ])

        @if($model instanceof \Thinktomorrow\Chief\Fragments\FragmentsOwner && $manager->can('fragments-index', $model))
            <x-chief::fragments :owner="$model"/>
        @endif

        @if($model->fragmentModel()->isShared())
            <div class="p-6 space-y-4 bg-orange-50 rounded-xl">
                <div>
                    <span class="text-xl font-semibold text-grey-900">Gedeeld fragment</span>
                </div>

                <div class="prose prose-dark">
                    <p>
                        Dit is een gedeeld fragment. Dit betekent dat het ook toegevoegd werd op een andere plaats op de website.
                        Elke aanpassing aan dit fragment zal dus op elke pagina doorgevoerd worden.
                    </p>

                    <p>
                        Dit fragment komt ook voor op:

                        @foreach(app(Thinktomorrow\Chief\Fragments\Actions\GetOwningModels::class)->get($model->fragmentModel()) as $otherOwner)
                            @if($otherOwner['model']->modelReference()->equals($owner->modelReference())) @continue @endif

                            @if(($otherOwner['model'] instanceof \Thinktomorrow\Chief\Fragments\Fragmentable))
                                <span class="link">
                                    {{ $otherOwner['model']->adminConfig()->getPageTitle() }}
                                </span>
                            @else
                                <a
                                    class="underline link link-primary"
                                    href="{{ $otherOwner['manager']->route('edit', $otherOwner['model']) }}"
                                >
                                    {{ $otherOwner['model']->adminConfig()->getPageTitle() }}
                                </a>
                            @endif

                            @if(!$loop->last), @endif
                        @endforeach
                    </p>
                </div>

                <button
                    class="btn btn-warning-outline"
                    type="submit"
                    form="detachSharedFragment{{ $model->modelReference()->get() }}"
                > Fragment niet meer delen en voortaan afzonderlijk bewerken op deze pagina </button>
            </div>
        @endif

        <div class="flex flex-wrap space-x-4">
            <button
                type="submit"
                form="updateForm{{ $model->modelReference()->get() }}"
                class="btn btn-primary"
            > Wijzigingen opslaan </button>

            <div data-vue-fields>
                @adminCan('fragment-delete', $model)
                    <a v-cloak @click="showModal('delete-fragment-{{ str_replace('\\','',$model->modelReference()->get()) }}')" class="cursor-pointer btn btn-error-outline">
                        @if($model->fragmentModel()->isShared())
                            Fragment verwijderen op deze pagina
                        @else
                            Fragment verwijderen
                        @endif
                    </a>
                @endAdminCan
            </div>
        </div>

        @if(!$model->fragmentModel()->isShared())
            <div>
                <button
                    class="btn btn-primary-outline"
                    type="submit"
                    form="copyFragment{{ $model->modelReference()->get() }}"
                > Fragment dupliceren op deze pagina </button>
            </div>
        @endif
    </div>
</form>

<div data-vue-fields>
    @include('chief::manager._transitions.modals.delete-fragment-modal')
</div>

<form
    id="copyFragment{{ $model->modelReference()->get() }}"
    method="POST"
    action="{{ $manager->route('fragment-copy', $owner, $model) }}"
> @csrf </form>

<form
    id="detachSharedFragment{{ $model->modelReference()->get() }}"
    method="POST"
    action="{{ $manager->route('fragment-unshare', $owner, $model) }}"
> @csrf </form>
