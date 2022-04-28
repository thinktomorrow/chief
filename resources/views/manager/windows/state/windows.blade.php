@foreach($stateConfigs as $stateConfig)
    @php
        $allowedToEdit = count(\Thinktomorrow\Chief\ManagedModels\States\State\StateMachine::fromConfig($model, $stateConfig)->getAllowedTransitions()) > 0;
    @endphp
    @include('chief::manager.windows.state.window')
@endforeach
