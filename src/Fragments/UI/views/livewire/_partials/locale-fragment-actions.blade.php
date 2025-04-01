@php
    $showInactiveLocales = false;
@endphp

@if ($showInactiveLocales)
    <x-chief::dialog.dropdown.item wire:click="hideInactiveLocales" variant="grey">
        <x-chief::icon.text-italic-slash />
        <x-chief::dialog.dropdown.item.content label="Toon alleen de actieve locales">
            <p>
                Dit zijn de locales die gekoppeld zijn aan de sites van deze pagina, die actief zijn voor dit gedeelde
                fragment.
            </p>
        </x-chief::dialog.dropdown.item.content>
    </x-chief::dialog.dropdown.item>
@else
    <x-chief::dialog.dropdown.item wire:click="showInactiveLocales" variant="grey">
        <x-chief::icon.text />
        <x-chief::dialog.dropdown.item.content label="Toon ook de inactieve locales">
            <p>
                Dit zijn de locales die gekoppeld zijn aan de sites van deze pagina, die niet actief zijn voor dit
                gedeelde fragment.
            </p>
        </x-chief::dialog.dropdown.item.content>
    </x-chief::dialog.dropdown.item>
@endif
