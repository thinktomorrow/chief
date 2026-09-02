<x-chief::dialog.modal wired>
    @if ($isOpen)
        @if ($transitionInConfirm = $this->getTransitionInConfirmationState())
            @include('chief-states::livewire._partials.edit-model-state-confirm')
        @else
            @include('chief-states::livewire._partials.edit-model-state-callouts')
        @endif
    @endif
</x-chief::dialog.modal>
