<x-chief::dialog.modal wired size="sm" title="Voeg tab toe"
                       subtitle="Je kan meerdere tabs met fragmenten beheren. Zo kan je specifieke fragmenten per site voorzien.">
    @if ($isOpen)
        <x-chief::form.fieldset rule="form.title">
            <x-chief::form.label for="title">Titel</x-chief::form.label>
            <x-chief::form.input.text id="title" wire:model="form.title" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset wire:ignore x-data="{showField: false}" rule="form.locales">
            <x-chief::link x-show="!showField" x-on:click="showField = true">
                <span>Wil je deze fragment gebruiken voor specifieke site(s)?</span>
            </x-chief::button>

            <div x-show="showField">

                <x-chief::form.label for="locales">
                    Site selectie
                </x-chief::form.label>

                <x-chief::form.description>
                    Deze fragmenten worden opgemaakt voor de site(s):
                </x-chief::form.description>

                <x-chief::multiselect
                    wire:model="form.locales"
                    :multiple="true"
                    :options="$this->getAvailableLocales()"
                    x-on:click="(e) => {
                    // Scroll to bottom of modal content so the multiselect dropdown is fully visible
                    e.target.closest(`[data-slot='content']`).scrollTo({top:9999, behavior: 'smooth'})
                }"
                />
            </div>


        </x-chief::form.fieldset>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">Toevoegen</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
