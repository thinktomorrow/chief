<a
    href="{{ $manager->route('edit', $model->getKey()) }}"
    title="Aanpassen"
    class="flex-shrink-0 link link-primary"
>
    <x-chief-icon-button icon="icon-edit"/>
</a>

@adminCan('preview', $model)
<a href="@adminRoute('preview', $model)" target="_blank" class="flex-shrink-0 link link-primary">
    <x-chief-icon-button icon="icon-external-link"/>
</a>
@endAdminCan

<options-dropdown class="link link-primary text-left">
    <div v-cloak class="dropdown-content">

        @if($manager->can('state-update', $model) && $model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract)
            @foreach ($model->getStateKeys() as $stateKey)
                    <?php
                    $stateConfig = $model->getStateConfig($stateKey);
                    $stateMachine = \Thinktomorrow\Chief\ManagedModels\States\State\StateMachine::fromConfig($model, $stateConfig);
                    ?>

                @foreach($stateMachine->getAllowedTransitions() as $transitionKey)
                    @include('chief::manager.windows.state.transition-options-dropdown-link', [
                        'model' => $model,
                        'transitionKey' => $transitionKey,
                        'stateConfig' => $stateConfig,
                    ])
                @endforeach
            @endforeach
        @endif

        @adminCan('duplicate', $model)
        @include('chief::manager._transitions.index.duplicate')
        @endAdminCan
    </div>
</options-dropdown>
