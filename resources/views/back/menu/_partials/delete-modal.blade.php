<modal id="delete-menuitem-{{$menuitem->id}}" class="large-modal" title=''>
    <form id="deleteForm-menuitem-{{$menuitem->id}}" v-cloak action="{{route('chief.back.menu.destroy', $menuitem->id)}}" method="POST">
        @method('DELETE')
        @csrf

        <h2>Wens je dit menu item te verwijderen?</h2>
        <p>Na verwijdering, zal het menu item onmiddellijk van de site verdwijnen.</p>

    </form>
    <div slot="footer">
        <button data-submit-form="deleteForm-menuitem-{{$menuitem->id}}" class="btn btn-o-tertiary inline-s" type="button"><span class="icon icon-trash"></span> verwijderen</button>
    </div>
</modal>
