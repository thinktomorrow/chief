@include('chief::manager._modals.delete-modal')

<a v-cloak @click="showModal('delete-manager-<?= $model->id; ?>')" class="dropdown-link dropdown-link-error">
    Verwijderen
</a>