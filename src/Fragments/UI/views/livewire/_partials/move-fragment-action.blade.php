@php
    $hasMoveDestinations = collect($moveDestinationOptions ?? [])->contains('disabled', false);
@endphp

<x-chief::dialog.dropdown.item
    x-on:click="$dispatch('open-dialog', { 'id': 'move-fragment-{{ $fragment->getId() }}' }); $wire.loadMoveDestinationOptions(); close();"
>
    <x-chief::icon.drag-drop-arrows />
    <x-chief::dialog.dropdown.item.content label="Fragment verplaatsen" />
</x-chief::dialog.dropdown.item>

<template x-teleport="body">
    <x-chief::dialog.modal id="move-fragment-{{ $fragment->getId() }}" title="Fragment verplaatsen" size="sm">
        @if ($moveDestinationOptions === null)
            <x-chief::form.description>Locaties laden...</x-chief::form.description>
        @elseif (! $hasMoveDestinations)
            <x-chief::form.description>
                Geen andere locaties beschikbaar voor dit fragment.
            </x-chief::form.description>
        @else
            <x-chief::form.fieldset rule="moveParentId">
                <x-chief::form.label for="move-parent-{{ $fragment->getId() }}">Nieuwe locatie</x-chief::form.label>

                <x-chief::form.description>
                    Het fragment wordt onderaan de gekozen locatie toegevoegd.
                </x-chief::form.description>

                <x-chief::multiselect
                    id="move-parent-{{ $fragment->getId() }}"
                    wire:model="moveParentId"
                    :options="$moveDestinationOptions"
                    placeholder="Kies een locatie"
                    dropdown-position="static"
                />
            </x-chief::form.fieldset>
        @endif

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button x-on:click="close">Annuleer</x-chief::button>
                @if ($hasMoveDestinations)
                    <x-chief::button wire:click="moveFragment" wire:loading.attr="disabled" variant="blue">
                        Verplaatsen
                    </x-chief::button>
                @endif
            </x-chief::dialog.modal.footer>
        </x-slot>
    </x-chief::dialog.modal>
</template>
