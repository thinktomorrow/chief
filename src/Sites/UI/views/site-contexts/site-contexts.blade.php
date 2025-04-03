@php
    $sites = $this->getSites();
@endphp

<x-chief::window title="Status">
    <x-slot name="actions">
        <x-chief::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
            <x-chief::icon.quill-write />
        </x-chief::button>
    </x-slot>

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
                    <p class="text-sm/5 text-grey-700">{{ $site->name }}</p>

                    @if ($activeContext = $this->findActiveContext($site->locale))
                        <x-chief::badge>
                            <span>{{ $activeContext->title }}</span>
                        </x-chief::badge>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <livewire:chief-wire::edit-site-contexts key="edit-site-links" :model-reference="$modelReference" />
</x-chief::window>
