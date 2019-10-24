<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @if($manager->isAssistedBy('publish') && $manager->assistant('publish')->hasPreviewUrl())
            <a class="block p-3 --link-with-bg" href="{!! $manager->assistant('publish')->previewUrl() !!}" target="_blank">Bekijk preview</a>
        @endif

        @if($manager->can('edit') && request()->fullUrl() !== $manager->route('edit'))
            <a href="{{ $manager->route('edit') }}" class="block p-3 --link-with-bg">Aanpassen</a>
        @endif

        @if($manager->can('update'))
            @if($manager->model() instanceof \Thinktomorrow\Chief\States\State\StatefulContract)
                @foreach(\Thinktomorrow\Chief\States\PageStatePresenter::fromModel($manager->model())->transitions() as $transition)
                    @include('chief::back.managers._transitions.'.$transition)
                @endforeach
            @else
                @if($manager->isAssistedBy('archive'))
                    @if($manager->assistant('archive')->isArchived())
                        @include('chief::back.managers._transitions.unarchive')
                    @else
                        @include('chief::back.managers._transitions.archive')
                    @endif
                @endif

                @if($manager->isAssistedBy('publish'))
                    @if($manager->assistant('publish')->isPublished())
                        @include('chief::back.managers._transitions.unpublish')
                    @else
                        @include('chief::back.managers._transitions.publish')
                    @endif
                @endif
            @endif
        @endif

    </div>
</options-dropdown>
