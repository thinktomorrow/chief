<x-chief-form::window class="card w-full">
    <span>CONTEXT: {{ $context->contextId }}</span>

    <!-- plus icon -->
    <div class="relative w-full">
        <div class="absolute flex justify-center w-full h-8 border-none cursor-pointer mt-[-16px] z-[1]">
            <div class="absolute">
                <x-chief::button
                    x-on:click="$wire.addFragment(0)">
                    <svg>
                        <use xlink:href="#icon-plus"></use>
                    </svg>
                </x-chief::button>
            </div>
        </div>
    </div>

    <div wire:ignore.self
         x-sortable
         x-sortable-group="{{ 'group-' . $context->contextId }}"
         x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
         class="divide-y divide-grey-100">
        @foreach($this->getRootFragments() as $fragment)
            <livewire:chief-fragments::fragment
                :key="$fragment->getId() . '-section'"
                :fragment="$fragment"
            />
        @endforeach
    </div>

    <livewire:chief-fragments::add-fragment
        :key="$context->contextId . '-add-fragment'"
        :context-id="$context->contextId"
        :parent-component-id="$this->getId()"
    />
</x-chief-form::window>
