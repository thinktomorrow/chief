<template x-teleport="body">
    <x-chief::dialog.modal id="delete-fragment-{{ $fragment->getId() }}" title="Verwijder dit fragment" size="xs">
        <div class="prose prose-dark prose-spacing">
            <p>Hiermee verwijder je dit fragment van deze pagina. Ben je zeker?</p>
            <p>Dit zal niet verwijderd worden op eventueel andere pagina's waar dit fragment gebruikt wordt.</p>
        </div>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button x-on:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="deleteFragment" variant="red">Verwijder fragment</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    </x-chief::dialog.modal>
</template>

<x-chief::dialog.dropdown.item
    x-on:click="$dispatch('open-dialog', { 'id': 'delete-fragment-{{ $fragment->getId() }}' }); close();"
    variant="red"
>
    <x-chief::icon.delete />
    <x-chief::dialog.dropdown.item.content
        :label="$fragment->isShared ? 'Fragment verwijderen op deze pagina' : 'Fragment verwijderen'"
    />
</x-chief::dialog.dropdown.item>
