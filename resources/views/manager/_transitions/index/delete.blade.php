@push('portals')
    @include('chief::manager._transitions.modals.delete-modal')
@endpush

<a v-cloak @click="showModal('delete-manager-<?= $model->id; ?>')" class="btn btn-error cursor-pointer">
    Verwijderen
</a>
