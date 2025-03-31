<x-chief::dialog.modal wired size="sm" title="Voeg een paginaopbouw toe">
    @if ($isOpen)

        {{-- TODO: show if creating second context --}}
        <x-chief::callout data-slot="form-group" variant="blue" title="Wat is een paginaopbouw?">
            <x-slot name="icon">
                <x-chief::icon.solid.information-diamond />
            </x-slot>

            <div class="space-y-2">
                <p>
                    Een paginaopbouw bepaalt de structuur van je pagina en hoe content wordt weergegeven per site.
                    Hiermee kan je:
                </p>

                <ul class="">
                    <li>Een opbouw per site maken</li>
                    <li>De volgorde van fragmenten bepalen</li>
                    <li>Een volledig nieuwe opbouw van de pagina voorbereiden</li>
                </ul>
            </div>
        </x-chief::callout>

        <x-chief::form.fieldset rule="form.title">
            <x-chief::form.label for="title">Titel</x-chief::form.label>
            <x-chief::form.input.text id="title" wire:model="form.title" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset rule="form.locales">
            <x-chief::form.label for="locales">Welke talen wens je te gebruiken in deze fragmenten?
            </x-chief::form.label>

            <x-chief::multiselect
                wire:model="form.locales"
                :multiple="true"
                :options="$this->getAvailableLocales()"
            />
        </x-chief::form.fieldset>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">Toevoegen</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
