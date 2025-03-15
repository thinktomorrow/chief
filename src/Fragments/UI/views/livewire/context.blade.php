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

    {{--    @dd($fragments)--}}

    <div wire:ignore.self
         x-sortable
         x-sortable-group="{{ 'group-' . $context->contextId }}"
         x-on:end.stop="setTimeout(() => $wire.reorder($event.target.sortable.toArray()), 300)"
         class="divide-y divide-grey-100">
        @foreach($fragments as $i => $fragment)
            @include('chief-fragments::livewire._partials.fragment')
        @endforeach
    </div>

    <livewire:chief-fragments::edit-fragment
        :key="$context->contextId . '-edit-fragment'"
        :context-id="$context->contextId"
        :parent-component-id="$this->getId()"
    />

    <livewire:chief-fragments::add-fragment
        :key="$context->contextId . '-add-fragment'"
        :context-id="$context->contextId"
        :parent-component-id="$this->getId()"
    />
</x-chief-form::window>
