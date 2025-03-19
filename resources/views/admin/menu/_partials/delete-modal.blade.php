<x-chief::dialog.modal id="delete-menuitem-{{ $menuitem->id }}" title="Verwijder dit menu item" size="xs">
    <form
        id="deleteForm-menuitem-{{ $menuitem->id }}"
        method="POST"
        action="{{ route('chief.back.menuitem.destroy', $menuitem->id) }}"
    >
        @method('DELETE')
        @csrf
    </form>

    <div class="prose prose-dark">
        <p>
            Je staat op het punt om
            <b>[{{ $menuitem->label }}]</b>
            te verwijderen. Eenmaal verwijderd, zal het menu item onmiddellijk van de site verdwijnen.
        </p>
    </div>

    <x-slot name="footer">
        <x-chief::dialog.modal.footer>
            <x-chief-table::button type="button" x-on:click.stop="close()">Annuleer</x-chief-table::button>
            <x-chief-table::button type="submit" form="deleteForm-menuitem-{{ $menuitem->id }}">
                Verwijder
            </x-chief-table::button>
        </x-chief::dialog.modal.footer>
    </x-slot>
</x-chief::dialog.modal>
