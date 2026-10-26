<template x-teleport="body">
    <x-chief::dialog.modal id="move-fragment-{{ $fragment->getId() }}" title="Fragment verplaatsen" size="sm">
        @php
            $destinations = $this->getMoveDestinations();
        @endphp

        <x-chief::form.fieldset>
            <x-chief::form.label for="move-parent-{{ $fragment->getId() }}">Nieuwe locatie</x-chief::form.label>

            <x-chief::form.input.select id="move-parent-{{ $fragment->getId() }}" wire:model="moveParentId">
                <option value="">Kies een locatie</option>

                @foreach ($destinations as $destination)
                    <option value="{{ $destination['id'] }}" @disabled ($destination['disabledReason'])>
                        {{ str_repeat('— ', $destination['depth']) }}{{ ucfirst($destination['label']) }}
                        @if ($destination['isCurrentLocation'])
                            (huidige locatie)
                        @elseif ($destination['disabledReason'])
                            (niet mogelijk)
                        @endif
                    </option>
                @endforeach
            </x-chief::form.input.select>

            @error ('moveParentId')
                <x-chief::callout data-slot="error" size="sm" variant="red" class="px-2.5 py-1">
                    {!! ucfirst($message) !!}
                </x-chief::callout>
            @enderror
        </x-chief::form.fieldset>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button x-on:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="moveFragment" wire:loading.attr="disabled" variant="blue">
                    Verplaatsen
                </x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    </x-chief::dialog.modal>
</template>

<x-chief::dialog.dropdown.item
    x-on:click="$dispatch('open-dialog', { 'id': 'move-fragment-{{ $fragment->getId() }}' }); close();"
>
    <x-chief::icon.drag-drop-arrows />
    <x-chief::dialog.dropdown.item.content label="Verplaatsen" />
</x-chief::dialog.dropdown.item>
