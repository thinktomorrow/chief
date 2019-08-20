<div class="border-t border-grey-100">
@if($manager->can('update') && $manager->isAssistedBy('archive'))
    @if(! $manager->assistant('archive')->isArchived())
        <a v-cloak @click="showModal('archive-manager-<?= str_slug($manager->assistant('archive')->route('archive')); ?>')" class="block p-3 text-warning --link-with-bg">
            Archiveer
        </a>
    @else
        <a data-submit-form="unarchiveForm-{{ $manager->details()->id }}" class="block p-3 text-warning --link-with-bg">Herstel</a>

        <form class="hidden" id="unarchiveForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('archive')->route('unarchive') }}" method="POST">
            {{ csrf_field() }}
            <button type="submit">Herstel</button>
        </form>

        @if($manager->can('delete'))
            <a v-cloak @click="showModal('delete-manager-<?= str_slug($manager->route('delete')); ?>')" class="block p-3 text-error --link-with-bg">
                Verwijderen
            </a>
        @endif
    @endif

@elseif($manager->can('delete'))
    <a v-cloak @click="showModal('delete-manager-<?= str_slug($manager->route('delete')); ?>')" class="block p-3 text-error --link-with-bg">
        Verwijderen
    </a>
@endif
</div>
