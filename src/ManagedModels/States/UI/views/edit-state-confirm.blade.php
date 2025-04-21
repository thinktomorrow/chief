<x-slot name="title">
    @if ($transitionInConfirm->confirmationTitle)
        {!! $transitionInConfirm->confirmationTitle !!}
    @endif
</x-slot>

<div class="space-y-6">
    @if ($errorMessage)
        <x-chief::callout variant="red">
            <p>{!! $errorMessage !!}</p>
        </x-chief::callout>
    @endif

    <div class="space-y-6">
        @foreach ($transitionInConfirm->confirmationFields as $field)
            {{ $field }}
        @endforeach

        @if ($transitionInConfirm->confirmationContent)
            <div class="prose prose-dark">
                <p>{!! $transitionInConfirm->confirmationContent !!}</p>
            </div>
        @endif
    </div>

</div>

<x-slot name="footer">
    <x-chief::dialog.modal.footer>
        <x-chief::button
            x-data="{}"
            x-on:click="$wire.saveState('{{ $transitionInConfirm->key }}')"
            variant="{{ $transitionInConfirm->variant }}"
        >
            {{ $transitionInConfirm->confirmationLabel }}
        </x-chief::button>
        <x-chief::button type="button" x-on:click="$wire.closeConfirm()">Annuleer</x-chief::button>
    </x-chief::dialog.modal.footer>
</x-slot>
