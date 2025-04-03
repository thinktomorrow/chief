<x-chief::dialog.modal wired size="sm" title="Voeg een inhoud toe">
    @if ($isOpen)
        <x-chief::form.fieldset rule="form.title">
            <x-chief::form.label for="title">Titel</x-chief::form.label>
            <x-chief::form.input.text id="title" wire:model="form.title" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset rule="form.locales">
            <x-chief::form.label for="locales">
                Welke talen wens je te gebruiken in deze fragmenten?
            </x-chief::form.label>

            <x-chief::multiselect
                wire:model="form.locales"
                :multiple="true"
                :options="$this->getAvailableLocales()"
                x-on:click="(e) => {
                    // Scroll to bottom of modal content so the multiselect dropdown is fully visible
                    e.target.closest(`[data-slot='content']`).scrollTo({top:9999, behavior: 'smooth'})
                }"
            />
        </x-chief::form.fieldset>

        <x-chief::callout data-slot="form-group" variant="blue">
            <x-slot name="icon">
                <x-chief::icon.solid.information-diamond />
            </x-slot>

            <div class="space-y-2">
                <p>
                    Je kan meerdere inhouden naast elkaar beheren. Zo kan je een paginaopbouw per site voorzien. Of een
                    variatie in teksten, afbeeldingen of volgorde
                    van fragmenten.
                </p>
            </div>
        </x-chief::callout>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">Toevoegen</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
