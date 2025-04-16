@php
    $sites = $this->getSiteLinks();
    // TODO(ben): Is there a better way to get the model here?
    $model = \Thinktomorrow\Chief\Shared\ModelReferences\ModelReference::fromString($this->modelReference)->instance();
@endphp

<x-chief::window title="Links">
    <x-slot name="actions">
        <x-chief::button wire:click="edit" size="sm" variant="grey" title="Links aanpassen" class="shrink-0">
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

                        @php
                            [$stateLabel, $stateVariant] = $site->status->influenceByModelState($model);
                        @endphp

                        <x-chief::badge :variant="$stateVariant">
                            {{ $stateLabel }}
                        </x-chief::badge>

                    </div>

                    @if($site->url)
                        <div class="flex items-start justify-between gap-2">
                            <x-chief::link size="xs" href="{{ $site->url->url }}" title="{{ $site->url->slug }}">
                                <span>{{ $site->url->url }}</span>
                                <x-chief::icon.link-square />
                            </x-chief::link>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="body text-grey-500">Nog geen sites geselecteerd.</p>
    @endif

    <livewire:chief-wire::edit-site-links key="edit-site-links" :model-reference="$modelReference" />
</x-chief::window>
