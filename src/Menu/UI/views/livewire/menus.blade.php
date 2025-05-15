@php
    $items = $this->getItems();
@endphp

<x-chief::window title="Menu items">
    <x-slot name="badges">
        @if (($item = $items->first(fn ($item) => $item->id === $activeItemId)) && count($locales) > 1)
            @foreach (\Thinktomorrow\Chief\Sites\ChiefSites::verifiedLocales($item->getAllowedSites()) as $site)
                <x-chief::badge
                    variant="{{ in_array($site, $item->getActiveSites()) ? 'blue' : 'outline-transparent' }}"
                    size="sm"
                >
                    {{ \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($site)->shortName }}
                </x-chief::badge>
            @endforeach
        @endif
    </x-slot>

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
