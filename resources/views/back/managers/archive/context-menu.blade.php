<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @if($manager->can('update'))
            @foreach(\Thinktomorrow\Chief\States\PageStatePresenter::fromModel($manager->existingModel())->transitions() as $transition)
                @include('chief::back.managers._transitions.'.$transition)
            @endforeach
        @endif

    </div>
</options-dropdown>
