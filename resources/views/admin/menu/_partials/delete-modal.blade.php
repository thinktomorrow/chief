<modal id="delete-menuitem-{{$menuitem->id}}" title="Menu item verwijderen">
    <form
        id="deleteForm-menuitem-{{$menuitem->id}}"
        method="POST"
        action="{{route('chief.back.menuitem.destroy', $menuitem->id)}}"
        v-cloak
    >
        @method('DELETE')
        @csrf

        <h2 class="h2 h1-dark">Het menuitem - {{ $menuitem->label }} - verwijderen?</h2>
        <p>Eenmaal verwijderd, zal het menuitem onmiddellijk van de site verdwijnen.</p>
    </form>

    <div slot="modal-action-buttons" v-cloak>
        <button form="deleteForm-menuitem-{{$menuitem->id}}" class="btn btn-error" type="submit">
            Verwijderen
        </button>
    </div>
</modal>
