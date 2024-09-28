@adminCan('duplicate')
<button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'edit-options' })">
    <x-chief::button>
        <svg class="h-5 w-5">
            <use xlink:href="#icon-ellipsis-vertical" />
        </svg>
    </x-chief::button>
</button>

<x-chief::dialog.dropdown id="edit-options">
    @adminCan('duplicate')
    @include('chief::manager._transitions.index.duplicate')
    @endAdminCan
</x-chief::dialog.dropdown>
@endAdminCan
