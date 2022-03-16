<div class="space-y-12">
    <div class="space-y-2">
        <p class="text-2xl display-base display-dark">
            {{ ucfirst($model->adminConfig()->getModelName()) }}
        </p>

        @include('chief::layout._partials.fragment_bookmarks')
    </div>

    <div class="space-y-6">
        {!! $slot !!}

        @if($model->fragmentModel()->exists)

            @if($model instanceof \Thinktomorrow\Chief\Fragments\FragmentsOwner && $manager->can('fragments-index', $model))
                <x-chief::fragments :owner="$model"/>
            @endif

            @if($model->fragmentModel()->isShared())
                <div class="p-6 space-y-4 bg-orange-50 rounded-xl">
                    <p class="text-xl display-base display-dark">Gedeeld fragment</p>

                    <div class="prose prose-spacing prose-dark">
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
                    >
                        Fragment niet meer delen en voortaan afzonderlijk bewerken op deze pagina
                    </button>
                </div>
            @endif

            <div class="flex flex-wrap space-x-4">
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
        @endif
    </div>
</div>

@if($model->fragmentModel()->exists)
    <div data-vue-fields>
        @include('chief::manager._transitions.modals.delete-fragment-modal')
    </div>

    <form
        id="detachSharedFragment{{ $model->modelReference()->get() }}"
        method="POST"
        action="{{ $manager->route('fragment-unshare', $owner, $model) }}"
    >
        @csrf
    </form>
@endif
