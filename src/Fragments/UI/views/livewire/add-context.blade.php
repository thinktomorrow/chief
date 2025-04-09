<x-chief::dialog.modal wired size="sm" title="Voeg tab toe"
                       subtitle="Je kan meerdere tabs met fragmenten beheren. Zo kan je specifieke fragmenten per site voorzien.">
    @if ($isOpen)
        <x-chief::form.fieldset rule="form.title">
            <x-chief::form.label for="title">Titel</x-chief::form.label>
            <x-chief::form.input.text id="title" wire:model="form.title" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset x-data="{showField: false}" rule="form.active_sites">
            <x-chief::button x-show="!showField" x-on:click="showField = true">
                <span>Deze fragmenten op een specifieke site tonen</span>
            </x-chief::button>

            <div x-show="showField">

                <x-chief::form.label for="active_sites">
                    Site
                </x-chief::form.label>

                <x-chief::form.description>
                    Deze fragmenten tab zal worden getoond op de geselecteerde sites
                </x-chief::form.description>

                <x-chief::multiselect
                    wire:model="form.active_sites"
                    :multiple="true"
                    :options="$this->getAvailableLocales()"
                    x-on:click="(e) => {
                    // Scroll to bottom of modal content so the multiselect dropdown is fully visible
                    e.target.closest(`[data-slot='content']`).scrollTo({top:9999, behavior: 'smooth'})
                }"
                />
            </div>


        </x-chief::form.fieldset>

        <x-chief::form.fieldset x-data="{showField: false}" rule="form.locales">
            <x-chief::button x-show="!showField" x-on:click="showField = true">
                <span>Fragmenten in een specifieke taal voorzien?</span>
            </x-chief::button>

            <div x-show="showField">

                <x-chief::form.label for="locales">
                    Geef hier de taalversies op die je wil voorzien.
                </x-chief::form.label>

                <x-chief::form.description>
                    Dit is enkel nodig als je fragmenten in een specifieke taal wil voorzien.
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
