@php
    $sites = $this->getSiteLinks();
@endphp

<x-chief::window title="Sites">
    <x-slot name="actions">
        <x-chief::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
            <x-chief::icon.quill-write />
        </x-chief::button>
    </x-slot>

    @if (count($sites) > 0)
        <div class="space-y-1">
            @foreach ($sites as $site)
                <div wire:key="site-link-{{ $site->locale }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-start gap-2">
                            @include('chief-sites::_partials.link-status-dot')

                            <div>
                                <p class="text-sm leading-6 text-grey-500">{{ $site->site->name }}</p>
                                <a href="{{ $site->url?->url }}" class="font-medium leading-6 text-grey-700">
                                    {{ $site->url->slug }}
                                </a>
                            </div>
                        </div>

                        @if ($site->contextId)
                            <x-chief-table::badge>
                                <span>{{ $site->contextTitle }}</span>
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
