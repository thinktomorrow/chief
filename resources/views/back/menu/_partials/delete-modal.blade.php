<modal id="delete-menuitem-{{$menuitem->id}}" class="large-modal" title=''>
    <form id="deleteForm-menuitem-{{$menuitem->id}}" v-cloak action="{{route('chief.back.menuitem.destroy', $menuitem->id)}}" method="POST">
        @method('DELETE')
        @csrf

        <h2>Het menuitem - {{ $menuitem->label }} - verwijderen?</h2>
        <p>Eenmaal verwijderd, zal het menuitem onmiddellijk van de site verdwijnen.</p>

    </form>

    <div slot="modal-action-buttons" v-cloak>
        <button data-submit-form="deleteForm-menuitem-{{$menuitem->id}}" class="btn btn-o-tertiary inline-s" type="button"><span class="icon icon-trash"></span> verwijderen</button>
    </div>
</modal>
