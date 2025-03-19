<x-chief::dialog.modal wired size="xs" title="Verwijder dit bestand">
    @if ($isOpen)
        {{-- Form prevents enter key in fields in this modal context to trigger submits of other form on the page --}}
        <form>
            <div class="prose prose-dark prose-spacing">
                <p>Weet je zeker dat je dit bestand wilt verwijderen? Dit kan niet ongedaan worden gemaakt.</p>
            </div>
        </form>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief-table::button type="button" x-on:click="close()">Annuleer</x-chief-table::button>
                <x-chief-table::button wire:click.prevent="submit" type="submit" variant="red">
                    Verwijder bestand
                </x-chief-table::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
