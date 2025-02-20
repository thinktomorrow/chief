@php
    use Thinktomorrow\Chief\Fragments\FragmentsOwner;
@endphp

@php
    use Thinktomorrow\Chief\Fragments\Fragmentable;
@endphp

@php
    use Thinktomorrow\Chief\Fragments\FragmentStatus;
@endphp

<div class="space-y-6 border-t border-grey-100 py-6">
    <div class="space-y-2">
        <p class="h6 h1-dark text-lg">
            {{ ucfirst($resource->getLabel()) }}
        </p>

        @if ($model->fragmentModel()->exists)
            @include('chief-fragments::components._partials.sidebar-fragment-bookmarks')
        @endif
    </div>

    {!! $slot !!}
</div>

@if ($model->fragmentModel()->exists)
    @if ($model instanceof FragmentsOwner && $manager->can('fragments-index', $model))
        <div class="border-t border-grey-100 py-9">
            <x-chief::fragments :owner="$model" />
        </div>
    @endif
@endif

@if ($model->fragmentModel()->exists)
    @if ($model->fragmentModel()->isShared())
        <div class="rounded-xl border border-orange-100 bg-orange-50 p-6">
            <p class="h6 h1-dark text-lg">Gedeeld fragment</p>

            <div class="prose prose-dark prose-spacing mt-4">
                <p>
                    Dit is een gedeeld fragment. Dat betekent dat het ook toegevoegd werd op een andere plaats op de
                    website. Elke aanpassing aan dit fragment zal dus doorgevoerd worden op de volgende pagina's:

                    @php
                        $otherOwners = collect(app(Thinktomorrow\Chief\Fragments\Actions\GetOwningModels::class)->get($model->fragmentModel()))->reject(function ($otherOwner) use ($owner) {
                            return $otherOwner['model']->modelReference()->equals($owner->modelReference());
                        });
                    @endphp

                    @foreach ($otherOwners as $otherOwner)
                        @if ($otherOwner['model'] instanceof Fragmentable)
                            <span class="link link-grey">
                                {{ $otherOwner['pageTitle'] }}
                            </span>
                        @else
                            <a
                                href="{{ $otherOwner['manager']->route('edit', $otherOwner['model']) }}"
                                title="{{ $otherOwner['pageTitle'] }}"
                                class="link link-primary underline"
                            >
                                {{ $otherOwner['pageTitle'] }}
                            </a>
                        @endif

                        @if (! $loop->last)
                            ,
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
@endif

@if ($model->fragmentModel()->exists)
    <div
        @class(['flex flex-wrap items-center gap-4 pt-6', 'border-t border-grey-100' => ! $model->fragmentModel()->isShared()])
    >
        <button
            type="submit"
            form="changeFragmentStatus{{ $model->modelReference()->get() }}"
            class="btn btn-grey icon-label"
        >
            @if ($model->fragmentModel()->isOnline())
                <x-chief::icon-label
                    position="append"
                    icon='<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /> </svg>'
                >
                    Zet offline
                </x-chief::icon-label>
            @else
                <x-chief::icon-label
                    position="append"
                    icon='<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /> </svg>'
                >
                    Zet online
                </x-chief::icon-label>
            @endif
        </button>

        <div>
            @adminCan('fragment-delete', $model)
            <a
                v-cloak
                x-on:click="$dispatch('open-dialog', { 'id': 'delete-fragment-{{ str_replace('\\', '', $model->modelReference()->get()) }}' })"
                class="link link-grey cursor-pointer"
            >
                @if ($model->fragmentModel()->isShared())
                    Fragment verwijderen op deze pagina
                @else
                    Fragment verwijderen
                @endif
            </a>
            @endAdminCan
        </div>
    </div>
@endif

@if ($model->fragmentModel()->exists)
    <div data-form data-form-tags="fragments">
        <div>
            @include('chief-fragments::components._partials.delete-fragment-modal')
        </div>

        <form
            id="detachSharedFragment{{ $model->modelReference()->get() }}"
            method="POST"
            action="{{ $manager->route('fragment-unshare', $owner, $model) }}"
        >
            @csrf
        </form>

        <form
            id="changeFragmentStatus{{ $model->modelReference()->get() }}"
            method="POST"
            action="{{ $manager->route('fragment-status', $model) }}"
        >
            <input
                type="hidden"
                name="online_status"
                value="{{
                    $model->fragmentModel()->isOnline()
                        ? FragmentStatus::offline->value
                        : FragmentStatus::online->value
                }}"
            />
            @csrf
        </form>
    </div>
@endif
