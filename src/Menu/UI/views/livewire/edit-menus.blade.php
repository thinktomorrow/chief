<x-chief::dialog.modal wired size="sm" title="Bewerk menus">
    @if ($isOpen)
        @include('chief-menu::livewire.edit-menus-items')

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
