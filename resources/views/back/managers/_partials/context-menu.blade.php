<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @if(contract($manager, \Thinktomorrow\Chief\Management\ManagerThatPreviews::class))
            <a class="block squished-s --link-with-bg" href="{!! $manager->previewUrl() !!}" target="_blank">Bekijk preview</a>
        @endif

        @if($manager->can('edit') && \Illuminate\Support\Facades\Route::currentRouteName() !== 'chief.back.managers.edit')
            <a href="{{ $manager->route('edit') }}" class="block squished-s --link-with-bg">Aanpassen</a>
        @endif

        @include('chief::back.managers._partials.publish-option')
        @include('chief::back.managers._partials.archive-delete-option')

    </div>
</options-dropdown>