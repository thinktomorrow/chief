<x-chief::dialog.modal wired size="sm" title="Bewerk menu">
    @if ($isOpen)

        <x-chief::form.fieldset rule="form.title">
            <x-chief::form.label for="title">Titel</x-chief::form.label>
            <x-chief::form.input.text id="title" wire:model="form.title" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset rule="form.locales">
            <x-chief::form.label for="locales">Welke talen wens je te gebruiken in deze items?
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
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>

        @if($cannotBeDeleted)
            <x-chief::callout data-slot="form-group" variant="red" title="Menuopbouw kan niet verwijderd worden">
                <x-slot name="icon">
                    <x-chief::icon.solid.alert />
                </x-slot>

                <div class="space-y-2">
                    @if($cannotBeDeletedBecauseOfLastLeft)
                        <p>
                            Deze opbouw is de enige opbouw die nog bestaat voor {{ $menu->typeTitle }}. Je kan het
                            laatste menu
                            niet verwijderen.
                        </p>
                    @elseif($cannotBeDeletedBecauseOfConnectedToSite)
                        <p>
                            Deze opbouw is nog gekoppeld aan één of meerdere sites. Verwijder eerst de koppeling om deze
                            opbouw te verwijderen.
                        </p>
                    @endif

                </div>
            </x-chief::callout>
        @else
            <x-chief::callout data-slot="form-group" variant="red" title="Menuopbouw verwijderen">
                <x-slot name="icon">
                    <x-chief::icon.solid.alert />
                </x-slot>

                <div class="space-y-2">
                    <p>Opgelet. Alle items in dit menu worden ook verwijderd.</p>

                    <div>
                        <x-chief::button variant="outline-red" x-on:click="$wire.deleteMenu()">
                            <x-chief::icon.delete />
                            <span>Verwijder menu</span>
                        </x-chief::button>
                    </div>
                </div>
            </x-chief::callout>
        @endif

    @endif
</x-chief::dialog.modal>
