<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @if($manager->isAssistedBy('publish'))
            <a class="block p-3 --link-with-bg" href="{!! $manager->assistant('publish')->previewUrl() !!}" target="_blank">Bekijk preview</a>
        @endif

        @if($manager->can('edit') && \Illuminate\Support\Facades\Route::currentRouteName() !== 'chief.back.managers.edit')
            <a href="{{ $manager->route('edit') }}" class="block p-3 --link-with-bg">Aanpassen</a>
        @endif

        @include('chief::back.managers._partials.publish-option')
        @include('chief::back.managers._partials.archive-delete-option')

    </div>
</options-dropdown>
