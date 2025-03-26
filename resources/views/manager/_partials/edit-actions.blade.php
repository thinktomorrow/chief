@adminCan('duplicate')
<x-chief::button x-data x-on:click="$dispatch('open-dialog', { 'id': 'edit-options' })" variant="outline-white">
    <span>Acties</span>
    <x-chief::icon.more-vertical-circle />
</x-chief::button>

<x-chief::dialog.dropdown id="edit-options">
    @adminCan('duplicate')
    @include('chief::manager._transitions.index.duplicate')
    @endAdminCan
</x-chief::dialog.dropdown>
@endAdminCan
