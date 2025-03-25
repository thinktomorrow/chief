@php

    $contexts = $this->getContexts();

@endphp

<div class="space-y-4">
    <div class="flex items-start justify-between gap-2">
        <nav aria-label="Tabs" role="tablist"
             class="flex items-start justify-start">
            @foreach ($contexts as $context)
                <button
                    type="button"
                    role="tab"
                    wire:click.prevent="showContext('{{ $context->id }}')"
                    aria-controls="{{ $context->id }}"
                    aria-selected="{{ $context->id === $activeContextId }}"
                    wire:key="menu-tabs-{{ $context->id }}"
                    @class([
                        'bui-btn font-normal ring-0 transition-all duration-150 ease-out bui-btn-sm py-[0.3125rem] *:h-[1.125rem]',
                        'bui-btn-grey text-grey-950' => ($context->id === $activeContextId),
                        'text-grey-700 bui-btn-outline-white' => ($context->id !== $activeContextId),
                    ])
                >{{ $context->title }}</button>
            @endforeach
        </nav>

        <x-chief::button wire:click="editContexts" variant="grey" size="xs">
            Beheren
        </x-chief::button>
    </div>

    @foreach ($contexts as $context)
        <div wire:key="context-tab-content-{{ $context->id }}">
            @if($context->id === $activeContextId)
                <livewire:chief-fragments::context :key="$context->id" :context="$context" />
            @endif
        </div>
    @endforeach

    <livewire:chief-wire::edit-contexts :model-reference="$modelReference" />
</div>
