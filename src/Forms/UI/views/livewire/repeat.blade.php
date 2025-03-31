<div
    data-slot="control"
    id="{{ $elementId }}"
    class="space-y-3"
    wire:ignore.self
    x-sortable
    x-sortable-group="{{ 'group-' . $elementId }}"
    x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
>

    @foreach ($form as $index => $values)
        <!-- repeat section -->
        <div wire:key="{{ $elementId . '-' . $index }}" x-sortable-item="{{ $index }}">
            <div class="space-y-2 py-4">
                <div class="flex items-start justify-end gap-3">
                    <x-chief::button
                        x-sortable-handle
                        size="sm"
                        variant="outline-white"
                        title="herschikken"
                        class="shrink-0"
                    >
                        <x-chief::icon.drag-drop-vertical />
                    </x-chief::button>

                    <div>
                        @foreach ($this->getFormComponents() as $formComponent)
                            @php
                                $this->prepareFormComponent($formComponent, $index);
                            @endphp

                            {!! $formComponent->render() !!}
                        @endforeach
                    </div>

                    <div>
                        <span wire:click="removeSection({{ $index }})">DELETE</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <x-chief::button wire:click="addSection" variant="grey">Voeg een extra blok toe</x-chief::button>
</div>
