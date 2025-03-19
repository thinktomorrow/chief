@php

    $siteLinks = $this->getSiteLinks();

@endphp
<x-chief::window title="Sites">

    <x-chief::button wire:click="edit" class="cursor-pointer text-xs">
        Aanpassen
    </x-chief::button>

    <div class="space-y-1">

        @if(count($siteLinks) > 0)
            @foreach($siteLinks as $siteLink)

                <div wire:key="site-link-{{ $siteLink->siteId }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-start gap-2">
                            @include('chief-sites::_partials.link-status-dot')

                            <div>
                                <p class="text-sm leading-6 text-grey-500">{{ $siteLink->site->name }}</p>
                                <a href="{{ $siteLink->url?->url }}"
                                   class="leading-6 text-grey-700">{{ $siteLink->url->slug }}</a>
                            </div>
                        </div>

                        @if($siteLink->contextId)
                            <x-chief-table::badge>
                                <span>{{ $siteLink->contextTitle }}</span>
                            </x-chief-table::badge>
                        @endif

                    </div>
                </div>

            @endforeach
        @else
            <span class="text-sm text-grey-600 py-1 px-2">Nog geen sites geselecteerd.</span>
        @endif
    </div>

    <livewire:chief-wire::edit-site-links key="edit-site-links" :model-reference="$modelReference" />

</x-chief::window>
