<div class="space-y-4">
    <div class="flex items-start justify-between gap-2">
        <x-chief::tabs wire:key="{{ Str::random() }}" reference="contextTabs" size="base" :show-tabs="false">
            @foreach ($this->getContexts() as $context)
                <x-chief::tabs.tab
                    wire:key="{{ Str::random() }}"
                    tab-id="{{ $context->contextId }}"
                    tab-label="{{ $context->label }}"
                />
            @endforeach
        </x-chief::tabs>

        <x-chief::button wire:click="open" variant="grey">Aanpassen</x-chief::button>
    </div>

    {{-- Tabs should only be visible if there's more than 1 context --}}
    <x-chief::tabs
        wire:key="{{ Str::random() }}"
        reference="contextTabs"
        :show-nav="false"
        :listen-for-external-tab="true"
        size="base"
    >
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
