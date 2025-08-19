@php
    $items = $this->getItems();
@endphp

<x-chief::window title="Fragmenten">
    @if ($this->allowMultipleItems() || count($items) > 1)
        <x-slot name="badges">
            @if (($item = $items->first(fn ($item) => $item->id === $activeItemId)) && count($locales) > 1)
                @foreach (\Thinktomorrow\Chief\Sites\ChiefSites::verifiedLocales($item->getAllowedSites()) as $site)
                    <x-chief::badge
                        variant="{{ in_array($site, $item->getActiveSites()) ? 'blue' : 'outline-transparent' }}"
                        size="sm"
                    >
                        {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($site) }}
                    </x-chief::badge>
                @endforeach
            @endif
        </x-slot>

        <x-slot name="tabs">
            @include('chief-fragments::livewire.tabitems.nav')
        </x-slot>
    @endif

    <div>
        @foreach ($items as $item)
            <div wire:key="context-tab-content-{{ $item->id }}">
                @if ($item->id === $activeItemId)
                    @if ($this->allowMultipleItems() || count($items) > 1)
                        @include('chief-fragments::livewire.tabitems.actions')
                    @endif

                    <livewire:chief-fragments::context
                        :key="$item->id"
                        :model="$model"
                        :context="$item"
                        :scoped-locale="$scopedLocale"
                    />
                @endif
            </div>
        @endforeach
    </div>

    <livewire:chief-wire::add-context :model-reference="$model->modelReference()" :locales="$locales" />
    <livewire:chief-wire::edit-context :model-reference="$model->modelReference()" :locales="$locales" />
</x-chief::window>
