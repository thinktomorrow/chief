@php
    $menus = $this->getMenus();
@endphp

<x-chief::window title="Menu items">
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
                </x-chief::window.tabs.item>
            @endforeach

            <x-chief::window.tabs.item wire:click="editMenus">
                <x-chief::icon.plus-sign class="size-5" />
            </x-chief::window.tabs.item>
        </x-chief::window.tabs>
    </x-slot>

    <x-slot name="badges">
        {{-- TODO: use active context instead --}}
        @foreach ($menu->locales as $locale)
            <x-chief::badge size="sm">{{ $locale }}</x-chief::badge>
        @endforeach
    </x-slot>

    <x-slot name="actions">
        <x-chief::button wire:click="editMenus" variant="grey" size="sm">
            <x-chief::icon.settings />
        </x-chief::button>
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
