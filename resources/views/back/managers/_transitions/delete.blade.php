@include('chief::back.managers._modals.delete-modal')

<a v-cloak @click="showModal('delete-manager-<?= $model->id; ?>')" class="block p-3 text-error --link-with-bg">
    Verwijderen
</a>
