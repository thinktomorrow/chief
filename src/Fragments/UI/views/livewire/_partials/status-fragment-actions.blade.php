@if ($fragment->isOnline)
    <x-chief::dialog.dropdown.item wire:click="putOffline" variant="orange">
        <x-chief::icon.view-off-slash />
        <x-chief::dialog.dropdown.item.content label="Zet offline" />
    </x-chief::dialog.dropdown.item>
@else
    <x-chief::dialog.dropdown.item wire:click="putOnline" variant="green">
        <x-chief::icon.view />
        <x-chief::dialog.dropdown.item.content label="Zet Online" />
    </x-chief::dialog.dropdown.item>
@endif
