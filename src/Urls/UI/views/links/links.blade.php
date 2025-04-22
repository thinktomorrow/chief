@php
    $links = $this->getLinks();
@endphp

<x-chief::window title="Links">
    <x-slot name="actions">
        <x-chief::button wire:click="edit" size="sm" variant="grey" title="Links aanpassen" class="shrink-0">
            <x-chief::icon.quill-write />
        </x-chief::button>
    </x-slot>

    @if (count($links) > 0)
        <div>
            @foreach ($links as $link)
                <div
                    wire:key="site-link-{{ $link->locale }}"
                    @class([
                        'space-y-1',
                        'mt-3 border-t border-grey-100 pt-3' => ! $loop->first,
                    ])
                >
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm/5 text-grey-700">{{ $link->site->name }}</p>

                        <x-chief::badge :variant="$link->stateVariant">
                            {{ $link->stateLabel }}
                        </x-chief::badge>
                    </div>

                    @if ($link->url)
                        <div class="flex items-start justify-between gap-2">
                            <x-chief::link
                                size="sm"
                                href="{{ $link->url->url }}"
                                title="{{ $link->url->slug }}"
                                class="break-all"
                            >
                                <span>{{ $link->url->url }}</span>
                                <x-chief::icon.link-square />
                            </x-chief::link>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="body text-grey-500">Nog geen links toegevoegd.</p>
    @endif

    <livewire:chief-wire::edit-links key="edit-links" :model="$this->getModel()" />
</x-chief::window>
