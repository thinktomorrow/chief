<div class="space-y-6">
    <p class="text-lg display-base display-dark">
        {{ ucfirst($resource->getLabel()) }}
    </p>

    @include('chief::layout._partials.fragment_bookmarks')

    {!! $slot !!}

    @if($model->fragmentModel()->exists)
        @if($model instanceof \Thinktomorrow\Chief\Fragments\FragmentsOwner && $manager->can('fragments-index', $model))
            <div class="py-3">
                <x-chief::fragments :owner="$model"/>
            </div>
        @endif

        @if($model->fragmentModel()->isShared())
            <div class="p-6 border border-orange-100 rounded-xl bg-orange-50">
                <p class="text-lg display-base display-dark">Gedeeld fragment</p>

                <div class="mt-4 prose prose-dark prose-spacing">
                    <p>
                        Dit is een gedeeld fragment. Dat betekent dat het ook toegevoegd werd op een andere plaats op de website.
                        Elke aanpassing aan dit fragment zal dus doorgevoerd worden op de volgende pagina's:

                        @foreach(app(Thinktomorrow\Chief\Fragments\Actions\GetOwningModels::class)->get($model->fragmentModel()) as $otherOwner)
                            @if($otherOwner['model']->modelReference()->equals($owner->modelReference())) @continue @endif

                            @if(!$loop->first), @endif

                            @if(($otherOwner['model'] instanceof \Thinktomorrow\Chief\Fragments\Fragmentable))
                                <span class="link link-grey">
                                    {{ $otherOwner['pageTitle'] }}
                                </span>
                            @else
                                <a
                                    href="{{ $otherOwner['manager']->route('edit', $otherOwner['model']) }}"
                                    title="{{ $otherOwner['pageTitle'] }}"
                                    class="underline link link-primary"
                                >
                                    {{ $otherOwner['pageTitle'] }}
                                </a>
                            @endif
                        @endforeach
                    </p>

                    <p>
                        Wil je een aanpassing maken aan dit fragment zonder dat je die doorvoert op de andere pagina's?
                        Koppel het fragment dan los op deze pagina.
                    </p>

                    <p>

                        <button
                            type="submit"
                            form="detachSharedFragment{{ $model->modelReference()->get() }}"
                            class="btn btn-warning-outline"
                        >
                            Fragment loskoppelen en afzonderlijk bewerken
                        </button>
                    </p>
                </div>
            </div>
        @endif

        <div class="flex flex-wrap gap-4">
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
</div>
