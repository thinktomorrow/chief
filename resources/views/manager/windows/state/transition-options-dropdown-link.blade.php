@if ($stateConfig->hasConfirmationForTransition($transitionKey))
    <x-chief::dialog.dropdown.item
        x-data
        x-on:click="$dispatch('open-dialog', { id: 'state-modal-{{ $transitionKey }}-{{ $model->id }}' })"
    >
        {{ ucfirst($stateConfig->getTransitionButtonLabel($transitionKey)) }}
    </x-chief::dialog.dropdown.item>

    @push('portals')
        @include('chief::manager.windows.state.state-modal')
    @endpush
@else
    <form
        id="state-form-{{ $transitionKey }}-{{ $model->id }}"
        method="POST"
        action="@adminRoute('state-update', $model, $stateConfig->getStateKey(), $transitionKey)"
    >
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @foreach ($stateConfig->getTransitionFields($transitionKey, $model) as $field)
                {{ $field->render() }}
            @endforeach
        </div>
    </form>

    <x-chief::dialog.dropdown.item form="state-form-{{ $transitionKey }}-{{ $model->id }}">
        {{ ucfirst($stateConfig->getTransitionButtonLabel($transitionKey)) }}
    </x-chief::dialog.dropdown.item>
@endif
