@push('portals')
    @include('chief::manager._transitions.modals.archive-modal')
@endpush

<a v-cloak @click="showModal('archive-manager-<?= $model->id ?>')" class="btn btn-warning cursor-pointer">
    Archiveer deze pagina
</a>
