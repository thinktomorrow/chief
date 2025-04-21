@adminCan('update')
<x-chief::button x-data x-on:click="$dispatch('open-dialog', { 'id': 'edit-options' })" variant="outline-white">
    <span>Acties</span>
    <x-chief::icon.more-vertical-circle />
</x-chief::button>

<x-chief::dialog.dropdown id="edit-options">
    <x-chief::dialog.dropdown.item>
        <x-chief::icon.copy />
        <x-chief::dialog.dropdown.item.content label="Voeg een nieuwe taal toe" />
    </x-chief::dialog.dropdown.item>
</x-chief::dialog.dropdown>
@endAdminCan
