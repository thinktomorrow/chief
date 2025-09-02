<div
    data-slot="control"
    id="{{ $elementId }}"
    wire:ignore.self
    x-sortable
    x-sortable-group="{{ 'group-' . $elementId }}"
    x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
    class="border-grey-200 [&>[data-slot=repeat-item]+[data-slot=repeat-item]]:border-grey-200 rounded-xl border bg-white [&>[data-slot=repeat-item]+[data-slot=repeat-item]]:border-t"
>
    @foreach ($form as $index => $values)
        <div
            data-slot="repeat-item"
            wire:key="{{ $elementId . '-' . $index }}"
            x-sortable-item="{{ $index }}"
            class="flex items-start gap-3 p-3"
        >
            <x-chief::button
                x-sortable-handle
                tabindex="-1"
                size="sm"
                variant="grey"
                title="herschikken"
                class="shrink-0"
            >
                <x-chief::icon.drag-drop-arrows />
            </x-chief::button>

            <div class="grow">
                @foreach ($this->getFormComponents() as $formComponent)
                    @php
                        $this->prepareFormComponent($formComponent, $index);
                    @endphp
                    {!! $formComponent->render() !!}
                @endforeach
            </div>

            <x-chief::button
                tabindex="-1"
                size="sm"
                variant="outline-red"
                class="shrink-0"
                wire:click="removeSection({{ $index }})"
            >
                <x-chief::icon.delete />
            </x-chief::button>
        </div>
    @endforeach

    <div class="pointer-events-none relative">
        <div class="absolute z-[1] flex h-8 w-full justify-center">
            <x-chief::button wire:click="addSection" size="sm" class="pointer-events-auto absolute -top-3.5">
                <x-chief::icon.plus-sign />
            </x-chief::button>
        </div>
    </div>
</div>
