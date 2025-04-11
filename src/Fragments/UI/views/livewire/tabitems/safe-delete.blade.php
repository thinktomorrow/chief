@if ($cannotBeDeleted)
    <x-chief::callout data-slot="form-group" variant="red" title="Tab kan niet verwijderd worden">
        <x-slot name="icon">
            <x-chief::icon.solid.alert />
        </x-slot>

        <div class="space-y-2">
            @if ($cannotBeDeletedBecauseOfLastLeft)
                <p>
                    Deze tab is de enige die nog bestaat voor deze pagina. Je kan de laatste
                    tab niet verwijderen.
                </p>
            @elseif ($cannotBeDeletedBecauseOfConnectedToSite)
                <p>
                    Deze tab staat live op één of meerdere sites. Verwijder eerst de koppeling om deze
                    tab te verwijderen.
                </p>
            @endif
        </div>
    </x-chief::callout>
@else
    <x-chief::callout data-slot="form-group" variant="red" title="Paginaopbouw verwijderen">
        <x-slot name="icon">
            <x-chief::icon.solid.alert />
        </x-slot>

        <div class="space-y-2">
            <p>Opgelet. Alle items in de tab worden ook verwijderd.</p>

            <div>
                <x-chief::button variant="outline-red" x-on:click="$wire.deleteItem()">
                    <x-chief::icon.delete />
                    <span>Verwijder tab én inhoud</span>
                </x-chief::button>
            </div>
        </div>
    </x-chief::callout>
@endif
