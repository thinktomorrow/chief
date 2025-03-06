<x-chief-form::window class="card w-full">
    <span>CONTEXT: {{ $context->contextId }}</span>

    <div wire:ignore.self
         x-sortable
         x-sortable-group="{{ 'group-' . $context->contextId }}"
         x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
         class="divide-y divide-grey-100">
        @foreach($this->getFragments() as $fragment)
            <livewire:chief-fragments::section
                :key="$fragment->getFragmentId() . '-' . $context->contextId"
                :context="$context"
                :original-fragment="$fragment"
            />
        @endforeach
    </div>
</x-chief-form::window>
