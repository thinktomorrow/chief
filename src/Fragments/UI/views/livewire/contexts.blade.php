@php
    $contexts = $this->getContexts();
@endphp

<x-chief::window title="Fragmenten">
    <x-slot name="tabs">
        <x-chief::window.tabs>
            @foreach ($contexts as $context)
                <x-chief::window.tabs.item
                    aria-controls="{{ $context->id }}"
                    aria-selected="{{ $context->id === $activeContextId }}"
                    wire:key="menu-tabs-{{ $context->id }}"
                    wire:click.prevent="showContext('{{ $context->id }}')"
                    :active="$context->id === $activeContextId"
                >
                    {{ $context->title }}
                </x-chief::window.tabs.item>
            @endforeach

            <x-chief::window.tabs.item wire:click="addContext">
                <x-chief::icon.plus-sign class="size-5" />
            </x-chief::window.tabs.item>
        </x-chief::window.tabs>
    </x-slot>

    @foreach ($contexts as $context)
        <div wire:key="context-tab-content-{{ $context->id }}" class="-mb-4">
            @if ($context->id === $activeContextId)

                <x-slot name="badges">
                    @foreach ($context->locales as $locale)
                        <x-chief::badge size="sm">{{ $locale }}</x-chief::badge>
                    @endforeach
                </x-slot>

                <x-slot name="actions">
                    <x-chief::button wire:click="editContext({{ $context->id }})" variant="grey" size="sm">
                        <x-chief::icon.settings />
                    </x-chief::button>
                </x-slot>

                <livewire:chief-fragments::context :key="$context->id" :context="$context" />
            @endif
        </div>
    @endforeach

    <livewire:chief-wire::add-context :model-reference="$modelReference" />
    <livewire:chief-wire::edit-context :model-reference="$modelReference" />
</x-chief::window>
