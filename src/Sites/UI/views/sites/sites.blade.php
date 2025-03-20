@php
    $sites = $this->getSites();
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
                <div wire:key="site-{{ $site->locale }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-start gap-2">
                            <div>
                                <p class="text-sm leading-6 text-grey-500">{{ $site->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="body text-grey-500">Nog geen sites geselecteerd.</p>
    @endif

    <livewire:chief-wire::edit-sites key="edit-sites" :model-reference="$modelReference" />
</x-chief::window>
