<div>
    <div
        wire:ignore.self
        x-sortable
        x-sortable-group="{{ 'group-' . $context->id }}"
        x-sortable-ghost-class="fragment-sort-ghost"
        x-sortable-drag-class="fragment-sort-drag"
        x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
        class="border-grey-100 [&>[data-slot=fragment]+[data-slot=fragment]]:border-grey-100 [&>[data-slot=fragment]+[data-slot=fragment]]:border-t border-y"
    >
        @if ($fragments->count() > 0)
            @include (
                'chief-fragments::livewire._partials.add-fragment-button',
                [
                    'order' => -1,
                    'parentId' => null,
                ]
            )

            @foreach ($fragments as $fragment)
                @include (
                    'chief-fragments::livewire._partials.fragment',
                    [
                        'parentId' => $fragment->parentId,
                    ]
                )
            @endforeach
        @else
            @include (
                'chief-fragments::livewire._partials.empty-context',
                [
                    'parentId' => null,
                ]
            )
        @endif
    </div>

    <template x-teleport="body">
        <livewire:chief-wire-fragments::edit-fragment
            :key="$context->id . '-edit-fragment'"
            :model="$this->getModel()"
            :context="$context"
            :parent-component-id="$this->getId()"
        />
    </template>

    <template x-teleport="body">
        <livewire:chief-wire-fragments::add-fragment
            :key="$context->id . '-add-fragment'"
            :context="$context"
            :parent-component-id="$this->getId()"
        />
    </template>
</div>
