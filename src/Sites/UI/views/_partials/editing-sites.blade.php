<div>
    @include('chief-sites::_partials.edit-site-link-items')

    @if (count($siteLinks) < \Thinktomorrow\Chief\Sites\ChiefSites::all()->count())
        <x-chief-table::button wire:click="addSites" variant="blue">Voeg site toe</x-chief-table::button>
    @endif
</div>

<x-slot name="footer">
    <x-chief::dialog.modal.footer>
        <x-chief-table::button wire:click="close">Annuleer</x-chief-table::button>
        <x-chief-table::button wire:click="save" variant="blue">Bewaren</x-chief-table::button>
    </x-chief::dialog.modal.footer>
</x-slot>
