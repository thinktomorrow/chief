@include('chief::back.managers._modals.delete-modal')

<a v-cloak @click="showModal('delete-manager-<?= $model->id; ?>')" class="btn btn-error">
    Verwijderen
</a>
