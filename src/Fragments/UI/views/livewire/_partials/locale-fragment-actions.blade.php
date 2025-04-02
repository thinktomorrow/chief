@if ($this->showsDormantLocales)
    <x-chief::dialog.dropdown.item wire:click="hideDormantLocales" variant="grey">
        <x-chief::icon.text-italic-slash />
        <x-chief::dialog.dropdown.item.content label="Toon enkel {{ implode(',', $context->locales) }}">
            <p>
                Toon enkel de talen die gebruikt worden in deze paginaopbouw.
            </p>
        </x-chief::dialog.dropdown.item.content>
    </x-chief::dialog.dropdown.item>
@else
    <x-chief::dialog.dropdown.item wire:click="showDormantLocales" variant="grey">
        <x-chief::icon.text />
        <x-chief::dialog.dropdown.item.content label="Toon alle gebruikte talen">
            <p>
                Toon alle talen van dit gedeelde fragment.
            </p>
        </x-chief::dialog.dropdown.item.content>
    </x-chief::dialog.dropdown.item>
@endif
