@include('chief::back.managers._modals.archive-modal')

<a v-cloak @click="showModal('archive-manager-<?= str_slug($manager->assistant('archive')->route('archive')); ?>')" class="block p-3 text-warning --link-with-bg">
    Archiveer
</a>
