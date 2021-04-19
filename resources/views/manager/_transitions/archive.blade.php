@include('chief::manager._modals.archive-modal')

<a v-cloak @click="showModal('archive-manager-<?= $model->id ?>')" class="dropdown-link hover:bg-orange-50 hover:text-orange-500">
    Archiveer
</a>
