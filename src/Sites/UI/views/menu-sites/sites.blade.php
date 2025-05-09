@php
    $sites = $this->getSites();
@endphp

<x-chief::window title="Sites">
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

                    @if ($activeMenu = $this->findActiveMenu($site->locale))
                        <x-chief::badge>
                            <span>{{ $activeMenu->title }}</span>
                        </x-chief::badge>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <livewire:chief-wire::menu-edit-sites key="menu-edit-sites-{{ $type }}" :type="$type" />
</x-chief::window>
