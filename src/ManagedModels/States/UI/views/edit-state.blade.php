<x-chief::dialog.modal wired>
    @if ($isOpen)

        @if ($transitionInConfirm = $this->getTransitionInConfirmationState())
            @include('chief-states::edit-state-confirm')
        @else
            @include('chief-states::edit-state-callouts')
        @endif
    @endif
</x-chief::dialog.modal>
