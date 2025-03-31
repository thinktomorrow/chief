<div
    data-slot="control"
    id="{{ $elementId }}"
    wire:ignore.self
    x-sortable
    x-sortable-group="{{ 'group-' . $elementId }}"
    x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
    class="divide-y divide-grey-100 rounded-xl border border-grey-100"
>
    @foreach ($form as $index => $values)
        <div
            wire:key="{{ $elementId . '-' . $index }}"
            x-sortable-item="{{ $index }}"
            class="flex items-start gap-3 p-4"
        >
            <x-chief::button x-sortable-handle size="sm" variant="outline-white" title="herschikken" class="shrink-0">
                <x-chief::icon.drag-drop-vertical />
            </x-chief::button>

            <div class="grow">
                @foreach ($this->getFormComponents() as $formComponent)
                    @php
                        $this->prepareFormComponent($formComponent, $index);
                    @endphp

                    {!! $formComponent->render() !!}
                @endforeach
            </div>

            <x-chief::button size="sm" variant="outline-red" class="shrink-0" wire:click="removeSection({{ $index }})">
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
