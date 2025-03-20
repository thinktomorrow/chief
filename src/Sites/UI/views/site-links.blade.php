@php
    $siteLinks = $this->getSiteLinks();
@endphp

<x-chief::window title="Sites">
    <x-slot name="actions">
        <x-chief-table::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
            <x-chief::icon.quill-write />
        </x-chief-table::button>
    </x-slot>

    @if (count($siteLinks) > 0)
        <div class="space-y-1">
            @foreach ($siteLinks as $siteLink)
                <div wire:key="site-link-{{ $siteLink->siteId }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-start gap-2">
                            @include('chief-sites::_partials.link-status-dot')

                            <div>
                                <p class="text-sm leading-6 text-grey-500">{{ $siteLink->site->name }}</p>
                                <a href="{{ $siteLink->url?->url }}" class="font-medium leading-6 text-grey-700">
                                    {{ $siteLink->url->slug }}
                                </a>
                            </div>
                        </div>

                        @if ($siteLink->contextId)
                            <x-chief-table::badge>
                                <span>{{ $siteLink->contextTitle }}</span>
                            </x-chief-table::badge>
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
