<x-chief::dialog.modal
    id="delete-fragment-{{ $fragment->getId() }}"
    title="Verwijder dit fragment"
    size="xs"
>
    <div class="prose prose-dark prose-spacing">
        <p>Hiermee verwijder je dit fragment van deze pagina. Ben je zeker?</p>
        <p>Dit zal niet verwijderd worden op eventueel andere pagina's waar dit fragment gebruikt wordt.</p>
    </div>

    <x-slot name="footer">
        <x-chief-table::button x-on:click="close" class="shrink-0">Annuleer</x-chief-table::button>
        <x-chief-table::button wire:click="deleteFragment" variant="red" class="shrink-0">
            Verwijder fragment
        </x-chief-table::button>
    </x-slot>
</x-chief::dialog.modal>

<a
    x-cloak
    x-on:click="$dispatch('open-dialog', { 'id': 'delete-fragment-{{ $fragment->getId() }}' })"
    class="cursor-pointer link link-grey"
>
    @if($fragment->isShared)
        Fragment verwijderen op deze pagina
    @else
        Fragment verwijderen
    @endif
</a>
