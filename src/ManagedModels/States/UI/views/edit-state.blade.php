<x-chief::dialog.modal wired>
    @if ($isOpen)
        @if ($transitionInConfirm = $this->getTransitionInConfirmationState())
            @include('chief-states::edit-state-confirm')
        @else
            @include('chief-states::edit-state-callouts')
        @endif
    @endif

    <x-slot name="footer">
        <x-chief::dialog.modal.footer>
            <x-chief::button type="button" wire:click.prevent="close">Annuleer</x-chief::button>
        </x-chief::dialog.modal.footer>
    </x-slot>
</x-chief::dialog.modal>
