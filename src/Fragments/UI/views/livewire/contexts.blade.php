<div class="space-y-4">
    <div>
        <x-chief-table::button wire:click="open">Aanpassen</x-chief-table::button>
    </div>

    {{-- Tabs should only be visible if there's more than 1 context --}}
    <x-chief::tabs wire:key="{{ Str::random() }}" :show-nav-as-buttons="true" reference="contextTabs" size="base">
        @foreach ($this->getContexts() as $context)
            <x-chief::tabs.tab
                wire:key="{{ Str::random() }}"
                tab-id="{{ $context->contextId }}"
                tab-label="{{ $context->label }}"
            >
                <livewire:chief-fragments::context :key="$context->contextId" :context="$context" />
            </x-chief::tabs.tab>
        @endforeach
    </x-chief::tabs>
</div>
