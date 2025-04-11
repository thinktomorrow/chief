<x-chief::dialog.modal wired size="sm" title="Fragmenten aanpassen">
    @if ($isOpen)
        <x-chief::form.fieldset rule="form.title">
            <x-chief::form.label for="title">Titel</x-chief::form.label>
            <x-chief::form.input.text id="title" wire:model="form.title" />
        </x-chief::form.fieldset>

        @if(count($this->getAvailableLocales()) > 1)
            @include('chief-fragments::livewire.tabitems.item-locales')
        @else
            @include('chief-fragments::livewire.tabitems.item-single-locale')
        @endif

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>

        @include('chief-fragments::livewire.tabitems.safe-delete')
    @endif
</x-chief::dialog.modal>
