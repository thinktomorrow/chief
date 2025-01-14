@adminCan('duplicate')
<x-chief-table::button x-data x-on:click="$dispatch('open-dialog', { 'id': 'edit-options' })" variant="outline-white">
    <span>Acties</span>
    <x-chief::icon.arrow-down />
</x-chief-table::button>

<x-chief::dialog.dropdown id="edit-options">
    @adminCan('duplicate')
    @include('chief::manager._transitions.index.duplicate')
    @endAdminCan
</x-chief::dialog.dropdown>
@endAdminCan
