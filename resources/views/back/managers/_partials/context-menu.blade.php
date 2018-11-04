<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @include('chief::back.managers._partials.preview-url')

        @if($manager->can('edit') && \Illuminate\Support\Facades\Route::currentRouteName() !== 'chief.back.managers.edit')
            <a href="{{ $manager->route('edit') }}" class="block squished-s --link-with-bg">Aanpassen</a>
        @endif

        @include('chief::back.managers._partials.publish-option')

        TODO: archiveer optie...

        @if($manager->can('delete'))
            <a v-cloak @click="showModal('delete-manager-<?= str_slug($manager->route('delete')); ?>')" class="block squished-s text-error --link-with-bg">
                Verwijderen
            </a>
        @endif

    </div>
</options-dropdown>