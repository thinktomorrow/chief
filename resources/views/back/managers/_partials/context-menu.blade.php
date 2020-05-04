<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @if($manager->isAssistedBy('publish') && $manager->assistant('publish')->hasUrl())
            <a class="block p-3 --link-with-bg" href="{!! $manager->assistant('publish')->url() !!}" target="_blank">Bekijk op site</a>
        @endif

        @if($manager->can('edit') && request()->fullUrl() !== $manager->route('edit'))
            <a href="{{ $manager->route('edit') }}" class="block p-3 --link-with-bg">Aanpassen</a>
        @endif

        @if($manager->can('update'))
            @if($manager->existingModel() instanceof \Thinktomorrow\Chief\States\State\StatefulContract)
                @foreach(\Thinktomorrow\Chief\States\PageStatePresenter::fromModel($manager->existingModel())->transitions() as $transition)
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

                @include('chief::back.managers._transitions.delete')
            @endif
        @endif


    </div>
</options-dropdown>
