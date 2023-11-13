<div>

    <x-chief::dialog wired size="md" title="test">
        @if($isOpen)
            @if($manager->can('state-update', $model) && $model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract)
                @foreach ($model->getStateKeys() as $stateKey)
                    @php
                        $stateConfig = $model->getStateConfig($stateKey);
                        $stateMachine = \Thinktomorrow\Chief\ManagedModels\States\State\StateMachine::fromConfig($model, $stateConfig);
                    @endphp

                    @foreach($stateMachine->getAllowedTransitions() as $transitionKey)
                        @include('chief::manager.windows.state.transition-options-dropdown-link', [
                        'model' => $model,
                        'transitionKey' => $transitionKey,
                        'stateConfig' => $stateConfig,
                        ])
                    @endforeach
                @endforeach
            @endif

            @if($manager->can('duplicate', $model))
                @include('chief::manager._transitions.index.duplicate')
            @endif
        @endif
    </x-chief::dialog>
</div>
