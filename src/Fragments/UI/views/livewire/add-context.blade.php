<x-chief::dialog.modal wired size="sm" title="Voeg tab toe"
                       subtitle="Je kan meerdere tabs met fragmenten beheren. Zo kan je specifieke fragmenten per site voorzien.">
    @if ($isOpen)
        <x-chief::form.fieldset rule="form.title">
            <x-chief::form.label for="title">Titel</x-chief::form.label>
            <x-chief::form.input.text id="title" wire:model="form.title" />
        </x-chief::form.fieldset>

        @if(count($this->getAvailableLocales()) > 1)
            @include('chief-fragments::livewire._partials.add-context-locales')
        @endif

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">Toevoegen</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
