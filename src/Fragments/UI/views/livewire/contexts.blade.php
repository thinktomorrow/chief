@php
    $items = $this->getItems();
@endphp

<x-chief::window title="Fragmenten">

    <x-slot name="tabs">
        @include('chief-fragments::livewire.tabitems.nav')
    </x-slot>

    <div class="-mb-4">
        @foreach ($items as $item)
            <div wire:key="context-tab-content-{{ $item->id }}">
                @if ($item->id === $activeItemId)
                    @include('chief-fragments::livewire.tabitems.actions')

                    <livewire:chief-fragments::context :key="$item->id" :context="$item" />
                @endif
            </div>
        @endforeach
    </div>

    <livewire:chief-wire::add-context :model-reference="$modelReference" :locales="$locales" />
    <livewire:chief-wire::edit-context :model-reference="$modelReference" :locales="$locales" />
</x-chief::window>
