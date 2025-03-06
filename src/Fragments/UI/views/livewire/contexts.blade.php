<div class="">

    <div class="border border-grey-400 flex gap-2 items-center rounded-lg justify-start">
        <x-chief::tabs wire:key="{{ Str::random() }}" :show-nav-as-buttons="true" reference="contextTabs" class="-mb-3">
            @foreach($this->getContexts() as $context)
                <x-chief::tabs.tab wire:key="{{ Str::random() }}" tab-id='{{ $context->id }}'>
                    <span>{{ $context->id }}</span>
                    <livewire:chief-fragments::context
                        :key="$context->id"
                        :context-id="$context->id"
                        :original-context="$context" />
                </x-chief::tabs.tab>
            @endforeach
        </x-chief::tabs>

        <x-chief::button wire:click="open" class="cursor-pointer">
            <svg class="w-5 h-5">
                <use xlink:href="#icon-ellipsis-vertical" />
            </svg>
        </x-chief::button>
    </div>

</div>


