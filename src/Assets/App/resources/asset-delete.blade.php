@php
    $componentId = \Illuminate\Support\Str::random();
@endphp

<x-chief::dialog wired size="sm">
    @if($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
        <form class="w-full space-y-6">
            <div class="space-y-3">
                <h2 class="font-medium h2-dark body">
                    Verwijder dit bestand
                </h2>
                <p class="body text-grey-500">
                    Weet je zeker dat je dit bestand wilt verwijderen? Dit kan niet ongedaan worden gemaakt.
                </p>
            </div>

            <div class="flex flex-wrap justify-end gap-3">
                <button type="button" x-on:click="open = false" class="btn btn-grey">
                    Annuleer
                </button>

                <button wire:click.prevent="submit" type="submit" class="btn btn-error">
                    Verwijder bestand
                </button>
            </div>
        </form>
    @endif
</x-chief::dialog>
