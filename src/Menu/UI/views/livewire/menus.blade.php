@php
    $menus = $this->getMenus();
@endphp

<x-chief::window>
    <x-slot name="tabs">
        <x-chief::window.tabs>
            @foreach ($menus as $menu)
                <x-chief::window.tabs.item
                    aria-controls="{{ $menu->id }}"
                    aria-selected="{{ $menu->id === $activeMenuId }}"
                    wire:key="menu-tabs-{{ $menu->id }}"
                    wire:click.prevent="showMenu('{{ $menu->id }}')"
                    :active="$menu->id === $activeMenuId"
                >
                    {{ $menu->title }}
                    {{-- <x-chief::badge>{{ Arr::join($menu->locales, ', ', ' en ') }}</x-chief::badge> --}}
                </x-chief::window.tabs.item>
            @endforeach

            <x-slot name="actions">
                <x-chief::button wire:click="editMenus" variant="grey" size="sm">
                    <x-chief::icon.settings />
                </x-chief::button>
            </x-slot>
        </x-chief::window.tabs>
    </x-slot>

    @foreach ($menus as $menu)
        <div wire:key="menu-tab-content-{{ $menu->id }}">
            @if ($menu->id === $activeMenuId)
                <livewire:chief-wire::table
                    :key="'table-'.$menu->id"
                    :table="$this->getMenuTable($menu->id)"
                    variant="transparent"
                />
            @endif
        </div>
    @endforeach

    <livewire:chief-wire::edit-menus :type="$type" />
</x-chief::window>
