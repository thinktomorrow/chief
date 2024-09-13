@php
    $componentId = \Illuminate\Support\Str::random();
@endphp

<x-chief::dialog.modal wired size="xs" title="Verwijder dit bestand">
    @if ($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
        <form class="w-full space-y-6">
            <p class="body text-grey-500">
                Weet je zeker dat je dit bestand wilt verwijderen? Dit kan niet ongedaan worden gemaakt.
            </p>
        </form>

        <x-slot name="footer">
            <button type="button" x-on:click="close()" class="btn btn-grey">Annuleer</button>

            <button wire:click.prevent="submit" type="submit" class="btn btn-error">Verwijder bestand</button>
        </x-slot>
    @endif
</x-chief::dialog.modal>
