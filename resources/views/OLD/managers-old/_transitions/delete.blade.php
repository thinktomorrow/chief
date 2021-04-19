@if($manager->can('delete'))
    @include('chief::manager._modals.delete-modal')

    <a v-cloak @click="showModal('delete-manager-<?= \Illuminate\Support\Str::slug($manager->route('delete')); ?>')" class="block p-3 text-error --link-with-bg">
        Verwijderen
    </a>
@endif
