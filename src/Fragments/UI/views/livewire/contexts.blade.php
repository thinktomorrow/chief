@php
    $contexts = $this->getContexts();
@endphp

<x-chief::window>
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
                    <x-chief::badge>{{ Arr::join($context->locales, ', ', ' en ') }}</x-chief::badge>
                </x-chief::window.tabs.item>
            @endforeach

            <x-slot name="actions">
                <x-chief::button wire:click="editContexts" variant="grey" size="sm">
                    <x-chief::icon.settings />
                </x-chief::button>
            </x-slot>
        </x-chief::window.tabs>
    </x-slot>

    @foreach ($contexts as $context)
        <div wire:key="context-tab-content-{{ $context->id }}">
            @if ($context->id === $activeContextId)
                <livewire:chief-fragments::context :key="$context->id" :context="$context" />
            @endif
        </div>
    @endforeach

    <livewire:chief-wire::edit-contexts :model-reference="$modelReference" />
</x-chief::window>
