@if($manager->isAssistedBy('archive'))
    @include('chief::back.managers._modals.archive-modal')

    <a v-cloak @click="showModal('archive-manager-<?= \Illuminate\Support\Str::slug($manager->assistant('archive')->route('archive')); ?>')" class="block p-3 text-warning --link-with-bg">
        Archiveer
    </a>
@endif
