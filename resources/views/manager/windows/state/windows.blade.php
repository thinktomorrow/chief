@foreach($stateConfigs as $stateConfig)
    @include('chief::manager.windows.state.window')
@endforeach
