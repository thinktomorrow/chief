<x-chief::dialog.drawer wired size="sm" :title="$addingSites ? 'Voeg links toe' : 'Bewerk links'">
    @if ($isOpen)
        @if ($addingSites)
            @include('chief-sites::_partials.adding-sites')
        @else
            <x-slot name="header">
                <x-chief::dialog.drawer.header>
                    @if (count($sites) < \Thinktomorrow\Chief\Sites\ChiefSites::all()->count())
                        <x-chief::button wire:click="addSites" variant="grey">
                            <x-chief::icon.plus-sign />
                            <span>Voeg site toe</span>
                        </x-chief::button>
                    @endif
                </x-chief::dialog.drawer.header>
            </x-slot>

            @include('chief-sites::site-links.edit-site-links-items')

            <x-slot name="footer">
                <x-chief::dialog.drawer.footer>
                    <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
                    <x-chief::button wire:click="close">Annuleer</x-chief::button>
                </x-chief::dialog.drawer.footer>
            </x-slot>
        @endif
    @endif
</x-chief::dialog.drawer>
