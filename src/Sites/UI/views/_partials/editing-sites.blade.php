<x-slot name="header">
    <x-chief::dialog.drawer.header>
        @if (count($siteLinks) < \Thinktomorrow\Chief\Sites\ChiefSites::all()->count())
            <x-chief-table::button wire:click="addSites" variant="grey">
                <x-chief::icon.plus-sign />
                <span>Voeg site toe</span>
            </x-chief-table::button>
        @endif
    </x-chief::dialog.drawer.header>
</x-slot>

@include('chief-sites::_partials.edit-site-link-items')

<x-slot name="footer">
    <x-chief::dialog.drawer.footer>
        <x-chief-table::button wire:click="save" variant="blue">Bewaren</x-chief-table::button>
        <x-chief-table::button wire:click="close">Annuleer</x-chief-table::button>
    </x-chief::dialog.drawer.footer>
</x-slot>
