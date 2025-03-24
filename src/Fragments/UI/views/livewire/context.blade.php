<x-chief::window class="!py-0">
    <div
        wire:ignore.self
        x-sortable
        x-sortable-group="{{ 'group-' . $context->contextId }}"
        x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
        class="[&>[data-slot=fragment]+[data-slot=fragment]]:border-t [&>[data-slot=fragment]+[data-slot=fragment]]:border-grey-100"
    >
        @include(
            'chief-fragments::livewire._partials.add-fragment-button',
            [
                'order' => -1,
                'parentId' => null,
            ]
        )

        @foreach ($fragments as $fragment)
            @include(
                'chief-fragments::livewire._partials.fragment',
                [
                    'parentId' => $fragment->parentId,
                ]
            )
        @endforeach
    </div>

    <livewire:chief-fragments::edit-fragment
        :key="$context->contextId . '-edit-fragment'"
        :context="$context"
        :parent-component-id="$this->getId()"
    />

    <livewire:chief-fragments::add-fragment
        :key="$context->contextId . '-add-fragment'"
        :context-id="$context->contextId"
        :parent-component-id="$this->getId()"
    />
</x-chief::window>
