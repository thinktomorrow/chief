<x-chief::dialog.drawer wired>
    @if ($isOpen)
        <x-slot name="title">
            {{ $this->getTitle() }}
        </x-slot>

        <x-slot name="subtitle">
            {!! $this->getContent() !!}
        </x-slot>

        <div class="space-y-6">
            @if ($errorMessage)
                <x-chief::callout variant="red">
                    <p>{!! $errorMessage !!}</p>
                </x-chief::callout>
            @endif

            @if ($transitionInConfirm = $this->getTransitionInConfirmationState())
                @if ($transitionInConfirm->confirmationContent)
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

                <x-chief::button
                    x-data
                    x-on:click="$wire.saveState('{{ $transitionInConfirm->key }}')"
                    variant="{{ $transitionInConfirm->variant}}"
                >
                    {{ $transitionInConfirm->label }}
                </x-chief::button>
            @else
                <div class="space-y-3">
                    @foreach ($this->getTransitions() as $transition)
                        <x-chief::callout
                            :variant="match($transition->key) {
                                'publish' => 'grey',
                                'archive' => 'orange',
                                'delete' => 'red',
                                default => 'grey',
                            }"
                        >
                            <div class="space-y-2">
                                @if ($transition->content)
                                    <p>
                                        {!! $transition->content !!}
                                    </p>
                                @endif

                                <x-chief::button
                                    x-data
                                    x-on:click="$wire.transition('{{ $transition->key }}')"
                                    :variant="match($transition->key) {
                                        'publish' => 'outline-blue',
                                        'archive' => 'outline-orange',
                                        'delete' => 'outline-red',
                                        default => 'outline-white',
                                    }"
                                >
                                    {{ $transition->label }}
                                </x-chief::button>
                            </div>
                        </x-chief::callout>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</x-chief::dialog.drawer>
