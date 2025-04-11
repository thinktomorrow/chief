@php
    $items = $this->getItems();
@endphp

<x-chief::window title="Menu items">

    <x-slot name="tabs">
        @include('chief-fragments::livewire.tabitems.nav')
    </x-slot>

    <div class="-mb-4">
        @foreach ($items as $item)
            <div wire:key="menu-tab-content-{{ $item->id }}">
                @if ($item->id === $activeItemId)
                    @include('chief-fragments::livewire.tabitems.actions')

                    <livewire:chief-wire::table
                        :key="'table-'.$item->getId()"
                        :table="$this->getMenuTable($item->getId())"
                        variant="transparent"
                    />
                @endif
            </div>
        @endforeach
    </div>

    <livewire:chief-wire::add-menu :type="$type" :locales="$locales" />
    <livewire:chief-wire::edit-menu :type="$type" :locales="$locales" />
</x-chief::window>
