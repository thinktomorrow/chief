@if ($stateConfig->hasConfirmationForTransition($transitionKey))
    <button
        type="button"
        x-data
        x-on:click="$dispatch('open-dialog', { id: 'state-modal-{{ $transitionKey }}-{{ $model->id }}' })"
    >
        <x-chief::dialog.dropdown.item>
            {{ ucfirst($stateConfig->getTransitionButtonLabel($transitionKey)) }}
        </x-chief::dialog.dropdown.item>
    </button>

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

    <button type="submit" form="state-form-{{ $transitionKey }}-{{ $model->id }}">
        <x-chief::dialog.dropdown.item>
            {{ ucfirst($stateConfig->getTransitionButtonLabel($transitionKey)) }}
        </x-chief::dialog.dropdown.item>
    </button>
@endif
