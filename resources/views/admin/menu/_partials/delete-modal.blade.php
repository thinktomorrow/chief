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
        <button type="button" x-on:click="open = false" class="btn btn-grey">Annuleer</button>

        <button type="submit" form="deleteForm-menuitem-{{ $menuitem->id }}" class="btn btn-error">Verwijder</button>
    </x-slot>
</x-chief::dialog.modal>
