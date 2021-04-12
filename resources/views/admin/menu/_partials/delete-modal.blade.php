<modal id="delete-menuitem-{{$menuitem->id}}" title="Menu item verwijderen">
    <form
        id="deleteForm-menuitem-{{$menuitem->id}}"
        method="POST"
        action="{{route('chief.back.menuitem.destroy', $menuitem->id)}}"
        v-cloak
    >
        @method('DELETE')
        @csrf

        <h2>Het menuitem - {{ $menuitem->label }} - verwijderen?</h2>
        <p>Eenmaal verwijderd, zal het menuitem onmiddellijk van de site verdwijnen.</p>
    </form>

    <div slot="modal-action-buttons" v-cloak>
        <button data-submit-form="deleteForm-menuitem-{{$menuitem->id}}" class="btn btn-error-filled" type="button">
            Verwijderen
        </button>
    </div>
</modal>
