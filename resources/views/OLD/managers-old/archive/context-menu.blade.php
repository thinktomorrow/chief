<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @if($manager->can('update'))
            @foreach(\Thinktomorrow\Chief\ManagedModels\States\PageStatePresenter::fromModel($manager->existingModel())->transitions() as $transition)
                @include('chief::manager._transitions.index.'.$transition)
            @endforeach
        @endif

    </div>
</options-dropdown>
