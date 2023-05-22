<div>
    <button type="button" wire:click="toggleDialog()">Open wired dialog</button>

    <x-chief::dialog wired>
        @if($isOpen)
            <p class="body body-dark">
                The wired dialog content
            </p>
        @endif
    </x-chief::dialog>

    <hr>

    <button type="button" x-data x-on:click="$dispatch('open-dialog', { 'id': 'the-normal-modal' })">
        Open normal dialog
    </button>

    <x-chief::dialog id="the-normal-modal">
        <p class="body body-dark">
            The normal dialog content
        </p>
    </x-chief::dialog>
</div>
