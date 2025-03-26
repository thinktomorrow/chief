<x-chief::dialog.drawer wired size="sm" title="Bewerk menus" :edge-to-edge="true">
    @if ($isOpen)
        <x-slot name="header">
            <x-chief::dialog.drawer.header>
                <x-chief::button wire:click="addMenu" variant="grey">
                    <x-chief::icon.plus-sign />
                    <span>Voeg een menu toe</span>
                </x-chief::button>
            </x-chief::dialog.drawer.header>
        </x-slot>

        @include('chief-menu::livewire.edit-menus-items')

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
