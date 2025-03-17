<x-chief::dialog.modal wired>
    @if ($isOpen)
        <x-slot name="title">
            {{ ucfirst($fragment->label) }}
        </x-slot>

        <x-slot name="subtitle">
            <span>id: {{ $this->getId() }} parent: {{ $this->parentComponentId }}</span>
            @include('chief-fragments::livewire._partials.bookmark')
        </x-slot>

        <div class="space-y-4">
            @foreach ($this->getFields() as $field)
                {{ $field }}
            @endforeach
        </div>

        <x-chief::form.fieldset>
            <x-chief::form.label>Fragmenten</x-chief::form.label>

            <div
                data-slot="control"
                wire:ignore.self
                x-sortable
                x-sortable-group="{{ 'group-fragment-' . $fragment->fragmentId }}"
                x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
                class="divide-y divide-grey-100"
            >
                @include(
                    'chief-fragments::livewire._partials.add-fragment-button',
                    [
                        'order' => -1,
                        'parentId' => $fragment->fragmentId,
                    ]
                )

                @foreach ($fragments as $childFragment)
                    @include(
                        'chief-fragments::livewire._partials.fragment',
                        [
                            'fragment' => $childFragment,
                            'parentId' => $fragment->fragmentId,
                        ]
                    )
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
        </x-chief::form.fieldset>

        @include('chief-fragments::livewire._partials.shared-fragment-actions')
        @include('chief-fragments::livewire._partials.status-fragment-actions')
        @include('chief-fragments::livewire._partials.delete-fragment-action')

        <x-slot name="footer">
            <x-chief-table::button wire:click="close" class="shrink-0">Annuleer</x-chief-table::button>
            <x-chief-table::button wire:click="save" variant="blue" class="shrink-0">Bewaren</x-chief-table::button>
        </x-slot>
    @endif
</x-chief::dialog.modal>
