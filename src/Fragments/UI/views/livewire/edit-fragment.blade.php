<x-chief::dialog.modal wired>
    @if ($isOpen)

        <x-slot name="title">
            {{ ucfirst($fragment->label) }}
        </x-slot>

        <x-slot name="subtitle">
            <span>
                id: {{ $this->getId() }}
                parent: {{ $this->parentComponentId }}
            </span>
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
                            x-on:click="$wire.addFragment(-1, '{{ $fragment->fragmentId }}')">
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
                @foreach($fragments as $childFragment)
                    @include('chief-fragments::livewire._partials.fragment', [
                        'fragment' => $childFragment,
                    ])
                @endforeach
            </div>

            <livewire:chief-fragments::edit-fragment
                :key="$fragment->getId() . '-edit-fragment'"
                :context="$context"
                :parent-component-id="$this->getId()"
            />

            <livewire:chief-fragments::add-fragment
                :key="$fragment->getId() . '-add-fragment'"
                :context-id="$context->contextId"
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
