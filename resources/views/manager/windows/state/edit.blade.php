<div data-form data-form-tags="status,links" class="space-y-6">
    <p class="text-lg display-base display-dark">Status beheren</p>

    @if($content = $stateConfig->getEditContent($model))
        <div class="prose prose-dark">
            <p>{!! $content !!}</p>
        </div>
    @endif

    @foreach($allowedTransitionKeys as $transitionKey)
        @if($stateConfig->hasConfirmationForTransition($transitionKey))
            @php
                $modalId = \Illuminate\Support\Str::random(10);
            @endphp

            <div data-vue-fields class="space-y-4">
                <div>
                    <a
                            v-cloak
                            @click="showModal('state-modal-<?= $modalId; ?>')"
                            class="cursor-pointer btn btn-{{ $stateConfig->getTransitionType($transitionKey) }}"
                    >
                        {{ $stateConfig->getTransitionLabel($transitionKey) }}
                    </a>
                </div>

                <modal id="state-modal-{{ $modalId }}" title="Ben je zeker?"
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
                    </form>


                    @if($content = $stateConfig->getTransitionContent( $transitionKey ))
                        <x-chief-inline-notification type="{{ $stateConfig->getTransitionType($transitionKey) }}" size="large">
                            <p>{!! $stateConfig->getTransitionContent( $transitionKey ) !!}</p>
                        </x-chief-inline-notification>
                    @endif

                    <div v-cloak slot="modal-action-buttons">
                        <button
                                form="state-modal-form-{{ $modalId }}"
                                type="submit"
                                class="btn btn-primary btn-{{ $stateConfig->getTransitionType($transitionKey) }}"
                        >
                            {{ $stateConfig->getTransitionLabel($transitionKey) }}
                        </button>
                    </div>
                </modal>
            </div>
        @else
            <form action="@adminRoute('state-update', $model, $stateConfig->getStateKey() ,$transitionKey)"
                  method="POST">
                {{ csrf_field() }}
                @method('PUT')

                <button type="submit"
                        class="btn btn-primary btn-{{ $stateConfig->getTransitionType($transitionKey) }}">
                    {{ $stateConfig->getTransitionLabel($transitionKey) }}
                </button>
            </form>

            @if($content = $stateConfig->getTransitionContent($transitionKey))
                <div class="prose prose-dark">
                    <p>{!! $stateConfig->getTransitionContent($transitionKey) !!}</p>
                </div>
            @endif
        @endif
    @endforeach
</div>
