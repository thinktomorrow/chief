@if ($fragment->isOnline)
    <x-chief::dialog.dropdown.item wire:click="putOffline" variant="grey">
        <x-chief::icon.view-off-slash />
        <x-chief::dialog.dropdown.item.content label="Zet offline">
            <p>Het fragment is dan niet meer zichtbaar op de live site, maar blijft hier wel bestaan.</p>
        </x-chief::dialog.dropdown.item.content>
    </x-chief::dialog.dropdown.item>
@else
    <x-chief::dialog.dropdown.item wire:click="putOnline" variant="green">
        <x-chief::icon.view />
        <x-chief::dialog.dropdown.item.content label="Zet Online">
            <p>Het fragment is dan weer zichtbaar op de live site.</p>
        </x-chief::dialog.dropdown.item.content>
    </x-chief::dialog.dropdown.item>
@endif
