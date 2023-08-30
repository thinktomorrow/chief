@if($stateConfig->hasConfirmationForTransition($transitionKey))
    <button
        type="button"
        x-data
        x-on:click="$dispatch('open-dialog', { id: 'state-modal-{{ $transitionKey }}-{{ $model->id }}' })"
        class="block cursor-pointer text-left dropdown-link dropdown-link-{{ $stateConfig->getTransitionType($transitionKey) }}"
    >
        {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}
    </button>

    @push('portals')
        @include('chief::manager.windows.state.state-modal')
    @endpush
@else
    <form method="POST" action="@adminRoute('state-update', $model, $stateConfig->getStateKey(), $transitionKey)">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @foreach($stateConfig->getTransitionFields( $transitionKey, $model ) as $field)
                {{ $field->render() }}
            @endforeach

            <button
                type="submit"
                class="block w-full text-left cursor-pointer dropdown-link dropdown-link-{{ $stateConfig->getTransitionType($transitionKey) }}"
            >
                {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}
            </button>
        </div>
    </form>
@endif
