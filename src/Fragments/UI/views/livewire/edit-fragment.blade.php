<x-chief::dialog.modal wired>
    @if ($isOpen)

        <x-slot name="title">
            {{ ucfirst($fragment->label) }}
        </x-slot>

        <x-slot name="subtitle">
            @include('chief-fragments::livewire._partials.bookmark')
        </x-slot>

        <div class="space-y-4">
            @foreach ($this->getFields() as $field)
                {{ $field }}
            @endforeach
        </div>

        <div class="space-y-4">
            <div class="prose prose-dark prose-spacing">
                Fragmenten
            </div>

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
                 x-sortable-group="{{ 'group-fragment-' . $fragment->fragmentId }}"
                 x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
                 class="divide-y divide-grey-100">
                @foreach($this->getFragments() as $childFragment)
                    <livewire:chief-fragments::fragment
                        :key="$childFragment->getId() . '-fragment'"
                        :fragment="$childFragment"
                    />
                @endforeach
            </div>

            <livewire:chief-fragments::add-fragment
                :key="$fragment->getId() . '-add-nested-fragment'"
                :context-id="$fragment->contextId"
                :parent-component-id="$this->getId()"
            />
        </div>

        @include('chief-fragments::livewire._partials.shared-fragment-actions')
        @include('chief-fragments::livewire._partials.status-fragment-actions')
        @include('chief-fragments::livewire._partials.delete-fragment-action')

        <x-slot name="footer">
            <x-chief-table::button wire:click="close" class="shrink-0">Annuleer</x-chief-table::button>
            <x-chief-table::button wire:click="save" variant="blue" class="shrink-0">
                Bewaren
            </x-chief-table::button>
        </x-slot>
    @endif
</x-chief::dialog.modal>
