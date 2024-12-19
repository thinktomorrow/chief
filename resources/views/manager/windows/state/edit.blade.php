<div class="border-t border-grey-100 pt-6">
    <div data-form data-form-tags="status,links" class="space-y-6">
        <p class="h6 h1-dark text-lg">Status beheren</p>

        @if ($content = $stateConfig->getEditContent($model))
            <div class="prose prose-dark">
                <p>{!! $content !!}</p>
            </div>
        @endif

        <div class="space-y-4">
            @foreach ($allowedTransitionKeys as $transitionKey)
                @if ($stateConfig->hasConfirmationForTransition($transitionKey))
                    <button
                        type="button"
                        x-on:click="$dispatch('open-dialog', { id: 'state-modal-{{ $transitionKey }}-{{ $model->id }}'})"
                        @class([
                            'btn',
                            'btn-primary' => $transitionKey === 'publish',
                            'btn-warning' => $transitionKey === 'archive',
                            'btn-error' => $transitionKey === 'delete',
                            'btn-grey' => ! in_array($transitionKey, ['publish', 'archive', 'delete']),
                        ])
                    >
                        {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}
                    </button>

                    <template x-teleport="body">
                        @include('chief::manager.windows.state.state-modal')
                    </template>
                @else
                    <form
                        action="@adminRoute('state-update', $model, $stateConfig->getStateKey(), $transitionKey)"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            @foreach ($stateConfig->getTransitionFields($transitionKey, $model) as $field)
                                {{ $field->render() }}
                            @endforeach

                            <button
                                type="submit"
                                @class([
                                    'btn',
                                    'btn-primary' => $transitionKey === 'publish',
                                    'btn-warning' => $transitionKey === 'archive',
                                    'btn-error' => $transitionKey === 'delete',
                                    'btn-grey' => ! in_array($transitionKey, ['publish', 'archive', 'delete']),
                                ])
                            >
                                {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}
                            </button>

                            @if ($content = $stateConfig->getTransitionContent($transitionKey))
                                <x-chief::inline-notification
                                    type="{{ $stateConfig->getTransitionType($transitionKey) }}"
                                    size="large"
                                >
                                    <p>
                                        {!! $content !!}
                                    </p>
                                </x-chief::inline-notification>
                            @endif
                        </div>
                    </form>
                @endif
            @endforeach
        </div>
    </div>
</div>
