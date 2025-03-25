@php
    use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
    use Thinktomorrow\Chief\Fragments\Fragment;
    use Thinktomorrow\Chief\Fragments\FragmentsOwner;
    use Thinktomorrow\Chief\Fragments\FragmentStatus;
@endphp

<div class="space-y-6 border-t border-grey-100 py-6">
    <div class="space-y-2">
        <p class="h6 h1-dark text-lg">
            {{ ucfirst($resource->getLabel()) }}
        </p>

        @if ($model->hasFragmentModel())
            @include('chief::layout._partials.fragment_bookmarks')
        @endif
    </div>

    <div>
        {{-- TODO: show all available contexts of owner - (display them as locales) - activate the current context --}}
        {{-- <livewire:chief-wire::fragment-locales --}}
        {{-- :resource-key="$resource::resourceKey()" --}}
        {{-- :modelReference="$model->fragmentModel()->modelReference()" --}}
        {{-- :locales="$model->fragmentModel()->getLocales()"/> --}}
    </div>

    {!! $slot !!}
</div>

@if ($model->hasFragmentModel())
    @if ($model instanceof FragmentsOwner)
        <div class="border-t border-grey-100 py-9">
            {{-- nested fragments --}}
            <x-chief-fragments::index :context-id="$context->id" />
        </div>
    @endif
@endif

@if ($model->hasFragmentModel())
    @if ($model->getFragmentModel()->isShared())
        <div class="rounded-xl border border-orange-100 bg-orange-50 p-6">
            <p class="h6 h1-dark text-lg">Gedeeld fragment</p>

            <div class="prose prose-dark prose-spacing mt-4">
                <p>
                    Dit is een gedeeld fragment. Dat betekent dat het ook toegevoegd werd op een andere plaats op de
                    website. Elke aanpassing aan dit fragment zal dus doorgevoerd worden op de volgende pagina's:

                    @php
                        $otherOwners = collect(app(ComposeLivewireDto::class)->getSharedFragmentDtos($model->getFragmentModel()))->reject(function ($otherOwner) use ($owner) {
                            return $otherOwner['model']->modelReference()->equals($owner->modelReference());
                        });
                    @endphp

                    @foreach ($otherOwners as $otherOwner)
                        @if ($otherOwner['model'] instanceof Fragment)
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
                        form="detachSharedFragment{{ $model->getFragmentId() }}"
                        class="btn btn-warning-outline"
                    >
                        Fragment loskoppelen en afzonderlijk bewerken
                    </button>
                </p>
            </div>
        </div>
    @endif
@endif

@if ($model->hasFragmentModel())
    <div
        @class(['flex flex-wrap items-center gap-4 pt-6', 'border-t border-grey-100' => ! $model->getFragmentModel()->isShared()])
    >
        <x-chief::button type="submit" form="changeFragmentStatus{{ $model->getFragmentId() }}" variant="grey">
            @if ($model->getFragmentModel()->isOnline())
                Zet offline
            @else
                Zet online
            @endif
        </x-chief::button>

        <div>
            @adminCan('fragment-delete', $model)
            <a
                x-on:click="$dispatch('open-dialog', { 'id': 'delete-fragment-{{ $model->getFragmentId() }}' })"
                class="link link-grey cursor-pointer"
            >
                @if ($model->getFragmentModel()->isShared())
                    Fragment verwijderen op deze pagina
                @else
                    Fragment verwijderen
                @endif
            </a>
            @endAdminCan
        </div>
    </div>
@endif

@if ($model->hasFragmentModel())
    <div data-form data-form-tags="fragments">
        <div>
            @include('chief-fragments::livewire._partials.delete-fragment-action')
        </div>

        <form
            id="detachSharedFragment{{ $model->getFragmentId() }}"
            method="POST"
            action="{{ route('chief::fragments.unshare', [$context->id, $model->getFragmentId()]) }}"
        >
            @csrf
        </form>

        <form
            id="changeFragmentStatus{{ $model->getFragmentId() }}"
            method="POST"
            action="{{ route('chief::fragments.status', [$context->id, $model->getFragmentId()]) }}"
        >
            <input
                type="hidden"
                name="online_status"
                value="{{
                    $model->getFragmentModel()->isOnline()
                        ? FragmentStatus::offline->value
                        : FragmentStatus::online->value
                }}"
            />
            @csrf
        </form>
    </div>
@endif
