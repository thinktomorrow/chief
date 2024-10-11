@adminCan('duplicate')
<x-chief-table::button x-data x-on:click="$dispatch('open-dialog', { 'id': 'edit-options' })" variant="tertiary">
    <x-chief::icon.more-vertical-circle />
</x-chief-table::button>

<x-chief::dialog.dropdown id="edit-options">
    @adminCan('duplicate')
    @include('chief::manager._transitions.index.duplicate')
    @endAdminCan
</x-chief::dialog.dropdown>
@endAdminCan
