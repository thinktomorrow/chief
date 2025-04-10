@php
    $contexts = $this->getContexts();
@endphp

<x-chief::window title="Fragmenten">

    <x-slot name="tabs">
        <x-chief::window.tabs>
            @foreach ($contexts as $i => $context)
                <x-chief::window.tabs.item
                    aria-controls="{{ $context->id }}"
                    aria-selected="{{ $context->id === $activeContextId }}"
                    wire:key="context-tabs-{{ $context->id }}"
                    wire:click.prevent="showContext('{{ $context->id }}')"
                    :active="$context->id === $activeContextId"
                >
                    {{ $context->title }}

                    @if($i == 0)
                        @foreach($this->getUnassignedActiveSites() as $unassignedSite)
                            <x-chief::badge
                                variant="grey"
                                size="xs">{{ \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($unassignedSite)->shortName }}</x-chief::badge>
                        @endforeach
                    @endif

                    @foreach($context->activeSites as $site)
                        <x-chief::badge
                            variant="grey"
                            size="xs">{{ \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($site)->shortName }}</x-chief::badge>
                    @endforeach
                </x-chief::window.tabs.item>
            @endforeach

            <x-chief::window.tabs.item wire:click="addContext">
                <x-chief::icon.plus-sign class="size-5" />
            </x-chief::window.tabs.item>
        </x-chief::window.tabs>
    </x-slot>

    <div class="-mb-4">
        @foreach ($contexts as $context)
            <div wire:key="context-tab-content-{{ $context->id }}">
                @if ($context->id === $activeContextId)
                    @if($this->showTabs())
                        <x-slot name="actions">
                            <x-chief::button wire:click="editContext({{ $context->id }})" variant="grey" size="sm">
                                <x-chief::icon.settings />
                            </x-chief::button>
                        </x-slot>
                    @endif

                    <livewire:chief-fragments::context :key="$context->id" :context="$context" />
                @endif
            </div>
        @endforeach
    </div>

    <livewire:chief-wire::add-context :model-reference="$modelReference" />
    <livewire:chief-wire::edit-context :model-reference="$modelReference" />
</x-chief::window>
