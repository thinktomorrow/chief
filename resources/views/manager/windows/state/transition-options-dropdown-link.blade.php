@if($stateConfig->hasConfirmationForTransition($transitionKey))
    @php
        $modalId = \Illuminate\Support\Str::random(10);
    @endphp

    <div data-vue-fields class="space-y-4">
        <div>
            <a
                v-cloak
                @click="showModal('state-modal-<?= $modalId; ?>')"
                class="block cursor-pointer dropdown-link dropdown-link-{{ $stateConfig->getTransitionType($transitionKey) }}"
            >
                {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}
            </a>
        </div>

        @push('portals')
            <modal
                id="state-modal-{{ $modalId }}"
                title="Ben je zeker?"
                url="{{ $stateConfig->getAsyncModalUrl($transitionKey, $model) }}"
                {{ $stateConfig->getAsyncModalUrl($transitionKey, $model) ? ' :footer=false' : '' }}
            >
                <form
                    id="state-modal-form-{{ $modalId }}"
                    action="@adminRoute('state-update', $model, $stateConfig->getStateKey() ,$transitionKey)"
                    method="POST"
                    v-cloak
                >
                    @csrf
                    @method('PUT')

                    @foreach($stateConfig->getTransitionFields( $transitionKey, $model ) as $field)
                        {{ $field->render() }}
                    @endforeach
                </form>

                <div v-cloak slot="modal-action-buttons">
                    <button
                        form="state-modal-form-{{ $modalId }}"
                        type="submit"
                        class="btn btn-primary btn-{{ $stateConfig->getTransitionType($transitionKey) }}"
                    >
                        {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}
                    </button>
                </div>
            </modal>
        @endpush
    </div>
@else
    <form action="@adminRoute('state-update', $model, $stateConfig->getStateKey() ,$transitionKey)"
          method="POST">
        {{ csrf_field() }}
        @method('PUT')

        <div class="relative space-y-6">
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
