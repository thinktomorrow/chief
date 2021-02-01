@include('chief::back.managers._modals.archive-modal')

<a v-cloak @click="showModal('archive-manager-<?= $model->id ?>')" class="block p-3 text-warning --link-with-bg">
    Archiveer
</a>
