@if($manager->can('delete'))
    @include('chief::back.managers._modals.delete-modal')

    <a v-cloak @click="showModal('delete-manager-<?= str_slug($manager->route('delete')); ?>')" class="block p-3 text-error --link-with-bg">
        Verwijderen
    </a>
@endif
