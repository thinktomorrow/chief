@php
    $sites = $this->getSites();
@endphp

<x-chief::window title="Sites">
    <x-slot name="actions">
        @if (count($menus) > 1)
            <x-chief::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
                <x-chief::icon.quill-write />
            </x-chief::button>
        @endif
    </x-slot>

    <div class="space-y-1">
        @foreach ($sites as $site)
            <div wire:key="site-{{ $site->locale }}">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-start gap-2">
                        <div>
                            <p class="text-sm leading-6 text-grey-500">{{ $site->name }}</p>
                        </div>
                    </div>
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
