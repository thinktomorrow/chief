<hr>
@if($manager->isAssistedBy('archive') && $manager->assistant('archive')->can('archive'))
    @if(! $manager->assistant('archive')->isArchived())
        <a data-submit-form="archiveForm-{{ $manager->details()->id }}" class="block squished-s text-warning --link-with-bg">Archiveer</a>
        <form class="--hidden" id="archiveForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('archive')->route('archive') }}" method="POST">
            {{ csrf_field() }}
            <button type="submit">Archiveer</button>
        </form>
    @else
        <a data-submit-form="unarchiveForm-{{ $manager->details()->id }}" class="block squished-s text-warning --link-with-bg">Herstel</a>

        <form class="--hidden" id="unarchiveForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('archive')->route('unarchive') }}" method="POST">
            {{ csrf_field() }}
            <button type="submit">Herstel</button>
        </form>

        @if($manager->can('delete'))
            <a v-cloak @click="showModal('delete-manager-<?= str_slug($manager->route('delete')); ?>')" class="block squished-s text-error --link-with-bg">
                Verwijderen
            </a>
        @endif
    @endif

@elseif($manager->can('delete'))
    <a v-cloak @click="showModal('delete-manager-<?= str_slug($manager->route('delete')); ?>')" class="block squished-s text-error --link-with-bg">
        Verwijderen
    </a>
@endif