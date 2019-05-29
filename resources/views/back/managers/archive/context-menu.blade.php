<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        <a data-submit-form="unarchiveForm-{{ $manager->details()->id }}" class="block squished-s text-warning --link-with-bg">Herstel</a>

        <form class="hidden" id="unarchiveForm-{{ $manager->details()->id }}" action="{{ $manager->assistant('archive')->route('unarchive') }}" method="POST">
            {{ csrf_field() }}
            <button type="submit">Herstel</button>
        </form>

        @if($manager->can('delete'))
            <a v-cloak @click="showModal('delete-manager-<?= str_slug($manager->route('delete')); ?>')" class="block squished-s text-error --link-with-bg">
                Verwijderen
            </a>
        @endif

    </div>
</options-dropdown>