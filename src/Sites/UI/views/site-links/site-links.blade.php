@php
    $sites = $this->getSiteLinks();
    // TODO(ben): Is there a better way to get the model here?
    $model = \Thinktomorrow\Chief\Shared\ModelReferences\ModelReference::fromString($this->modelReference)->instance();
@endphp

<x-chief::window title="Status">
    <x-slot name="actions">
        @if ($model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract && chiefAdmin()->can('update-page'))
            @foreach ($model->getStateKeys() as $stateKey)
                <livewire:chief-wire::state :model="$model" :state-key="$stateKey" />
            @endforeach
        @endif

        <x-chief::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
            <x-chief::icon.quill-write />
        </x-chief::button>
    </x-slot>

    @if (count($sites) > 0)
        <div>
            @foreach ($sites as $site)
                <div
                    wire:key="site-link-{{ $site->locale }}"
                    @class([
                        'space-y-1',
                        'mt-3 border-t border-grey-100 pt-3' => ! $loop->first,
                    ])
                >
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm/5 text-grey-700">{{ $site->site->name }}</p>

                        <x-chief::badge
                            :variant="match($site->status->value) {
                                'online' => 'green',
                                'offline' => 'grey',
                                default => 'grey',
                            }"
                        >
                            {{ $site->status->value }}
                        </x-chief::badge>
                    </div>

                    <div class="flex items-start justify-between gap-2">
                        <x-chief::link href="{{ $site->url?->url }}" title="{{ $site->url?->slug }}" class="leading-5">
                            {{ $site->url?->slug }}
                        </x-chief::link>

                        @if ($site->contextId)
                            <span class="inline-flex gap-0.5 text-sm/5 text-grey-500">
                                <x-chief::icon.link class="my-0.5 size-4" />
                                {{ $site->contextTitle }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="body text-grey-500">Nog geen sites geselecteerd.</p>
    @endif

    <livewire:chief-wire::edit-site-links key="edit-site-links" :model-reference="$modelReference" />
</x-chief::window>
