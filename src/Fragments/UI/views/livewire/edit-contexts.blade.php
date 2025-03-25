<x-chief::dialog.drawer
    wired
    size="sm"
    title="Bewerk pagina contexten"
    :edge-to-edge="true"
>
    @if ($isOpen)

        @include('chief-fragments::livewire.edit-contexts-items')

        <x-chief::button wire:click="addContext" variant="grey">
            <x-chief::icon.plus-sign />
            <span>Voeg een context toe</span>
        </x-chief::button>

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>

    @endif
</x-chief::dialog.drawer>
