<x-chief::dialog.drawer
    wired
    size="sm"
    title="Bepaal het menu per site"
    :edge-to-edge="true"
>
    @if ($isOpen)
        @include('chief-sites::menu-sites.edit-sites-items')

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
