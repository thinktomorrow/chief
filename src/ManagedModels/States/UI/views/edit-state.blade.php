<x-chief::dialog.drawer wired>
    @if ($isOpen)
        <x-slot name="title">
            {{ $this->getTitle() }}
        </x-slot>

        <x-slot name="subtitle">
            {!! $content !!}
        </x-slot>

        <div class="space-y-6">
            @if ($errorMessage)
                <x-chief::inline-notification type="error" size="large">
                    <p>{!! $errorMessage !!}</p>
                </x-chief::inline-notification>
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

                <x-chief-table::button
                    x-data
                    x-on:click="$wire.saveState('{{ $transitionInConfirm->key }}')"
                    variant="{{ $transitionInConfirm->variant}}"
                >
                    {{ $transitionInConfirm->label }}
                </x-chief-table::button>
            @else
                <div class="space-y-4">
                    @foreach ($this->getTransitions() as $transition)
                        <x-chief-table::button
                            x-data
                            x-on:click="$wire.transition('{{ $transition->key }}')"
                            variant="{{ $transition->variant}}"
                        >
                            {{ $transition->label }}
                        </x-chief-table::button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</x-chief::dialog.drawer>
