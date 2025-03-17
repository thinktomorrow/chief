<div class="w-full rounded-lg border border-grey-400">
    <div>
        <x-chief::button wire:click="open" class="cursor-pointer">Aanpassen</x-chief::button>
    </div>

    <div>
        <x-chief::tabs wire:key="{{ Str::random() }}" :show-nav-as-buttons="true" reference="contextTabs">
            @foreach ($this->getContexts() as $context)
                <x-chief::tabs.tab wire:key="{{ Str::random() }}" tab-id="{{ $context->contextId }}">
                    <span>{{ $context->contextId }}</span>
                    <livewire:chief-fragments::context :key="$context->contextId" :context="$context" />
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>
    </div>
</div>
