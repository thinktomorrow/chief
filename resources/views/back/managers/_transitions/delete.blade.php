@include('chief::back.managers._modals.delete-modal')

<a v-cloak @click="showModal('delete-manager-<?= $model->id; ?>')" class="dropdown-link hover:bg-red-50 hover:text-red-500">
    Verwijderen
</a>
