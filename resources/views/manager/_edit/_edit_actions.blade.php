@adminCan('duplicate')
<button x-data type="button" x-on:click="$dispatch('open-dialog', { 'id': 'edit-options' })">
    <x-chief-table::button color="white">
        <x-chief::icon.more-vertical-circle />
    </x-chief-table::button>
</button>

<x-chief::dialog.dropdown id="edit-options">
    @adminCan('duplicate')
    @include('chief::manager._transitions.index.duplicate')
    @endAdminCan
</x-chief::dialog.dropdown>
@endAdminCan
