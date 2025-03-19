<x-chief::dialog.modal wired>
    @if ($isOpen)

        <x-slot name="title">
            {{ $this->getTitle() }}
        </x-slot>

        <x-slot name="subtitle">
            @if ($content = $this->getContent())
                <div class="prose prose-dark">
                    <p>{!! $content !!}</p>
                </div>
            @endif
        </x-slot>

        <div class="space-y-6">

            @if($errorMessage)
                <x-chief::inline-notification type="error" size="large">
                    <p>{!! $errorMessage !!}</p>
                </x-chief::inline-notification>
            @endif

            @if($transitionInConfirm = $this->getTransitionInConfirmationState())
                @if($transitionInConfirm->confirmationContent)
                    {!! $transitionInConfirm->confirmationContent !!}
                @endif

                <div class="space-y-6">
                    @foreach ($transitionInConfirm->fields as $field)
                        {{ $field }}
                    @endforeach

                    @if ($content = $transitionInConfirm->content)
                        <div class="prose prose-dark">
                            <p>{!! $content !!}</p>
                        </div>
                    @endif
                </div>

                <x-chief-table::button x-data x-on:click="$wire.saveState('{{ $transitionInConfirm->key }}')"
                                       variant="{{ $transitionInConfirm->variant}}">
                    {{ $transitionInConfirm->label }}
                </x-chief-table::button>
            @else

                <div class="space-y-4">
                    @foreach ($this->getTransitions() as $transition)

                        <x-chief-table::button x-data x-on:click="$wire.transition('{{ $transition->key }}')"
                                               variant="{{ $transition->variant}}">
                            {{ $transition->label }}
                        </x-chief-table::button>

                        {{--                        <x-chief::button x-data x-on:click="$dispatch('open-dialog', { 'id': 'user-edit-options' });">--}}
                        {{--                            <x-chief::icon.more-vertical-circle class="size-5" />--}}
                        {{--                        </x-chief::button>--}}



                        {{--                        @if ($stateConfig->hasConfirmationForTransition($transitionKey))--}}
                        {{--                            <button--}}
                        {{--                                type="button"--}}
                        {{--                                x-on:click="$dispatch('open-dialog', { id: 'state-modal-{{ $transitionKey }}-{{ $model->id }}'})"--}}
                        {{--                                @class([--}}
                        {{--                                    'btn',--}}
                        {{--                                    'btn-primary' => $transitionKey === 'publish',--}}
                        {{--                                    'btn-warning' => $transitionKey === 'archive',--}}
                        {{--                                    'btn-error' => $transitionKey === 'delete',--}}
                        {{--                                    'btn-grey' => ! in_array($transitionKey, ['publish', 'archive', 'delete']),--}}
                        {{--                                ])--}}
                        {{--                            >--}}
                        {{--                                {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}--}}
                        {{--                            </button>--}}

                        {{--                            <template x-teleport="body">--}}
                        {{--                                @include('chief::manager.windows.state.state-modal')--}}
                        {{--                            </template>--}}
                        {{--                        @else--}}
                        {{--                            <form--}}
                        {{--                                action="@adminRoute('state-update', $model, $stateConfig->getStateKey(), $transitionKey)"--}}
                        {{--                                method="POST"--}}
                        {{--                            >--}}
                        {{--                                @csrf--}}
                        {{--                                @method('PUT')--}}

                        {{--                                <div class="space-y-6">--}}
                        {{--                                    @foreach ($stateConfig->getTransitionFields($transitionKey, $model) as $field)--}}
                        {{--                                        {{ $field->render() }}--}}
                        {{--                                    @endforeach--}}

                        {{--                                    <button--}}
                        {{--                                        type="submit"--}}
                        {{--                                        @class([--}}
                        {{--                                            'btn',--}}
                        {{--                                            'btn-primary' => $transitionKey === 'publish',--}}
                        {{--                                            'btn-warning' => $transitionKey === 'archive',--}}
                        {{--                                            'btn-error' => $transitionKey === 'delete',--}}
                        {{--                                            'btn-grey' => ! in_array($transitionKey, ['publish', 'archive', 'delete']),--}}
                        {{--                                        ])--}}
                        {{--                                    >--}}
                        {{--                                        {{ $stateConfig->getTransitionButtonLabel($transitionKey) }}--}}
                        {{--                                    </button>--}}

                        {{--                                    @if ($content = $stateConfig->getTransitionContent($transitionKey))--}}
                        {{--                                        <x-chief::inline-notification--}}
                        {{--                                            type="{{ $stateConfig->getTransitionType($transitionKey) }}"--}}
                        {{--                                            size="large"--}}
                        {{--                                        >--}}
                        {{--                                            <p>--}}
                        {{--                                                {!! $content !!}--}}
                        {{--                                            </p>--}}
                        {{--                                        </x-chief::inline-notification>--}}
                        {{--                                    @endif--}}
                        {{--                                </div>--}}
                        {{--                            </form>--}}
                        {{--                        @endif--}}
                    @endforeach
                </div>
            @endif
        </div>

    @endif
</x-chief::dialog.modal>
