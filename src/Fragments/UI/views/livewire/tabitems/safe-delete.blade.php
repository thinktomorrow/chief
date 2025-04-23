@if ($cannotBeDeleted)
    <x-chief::callout data-slot="form-group" variant="red" title="Versie kan niet verwijderd worden">
        <x-slot name="icon">
            <x-chief::icon.solid.alert />
        </x-slot>

        <div class="space-y-2">
            @if ($cannotBeDeletedBecauseOfLastLeft)
                <p>
                    Deze versie is de enige die nog bestaat voor deze pagina. Je kan de laatste versie niet verwijderen.
                </p>
            @elseif ($cannotBeDeletedBecauseOfConnectedToSite)
                <p>
                    Deze versie staat live op één of meerdere sites. Verwijder eerst de koppeling om deze versie te
                    verwijderen.
                </p>
            @endif
        </div>
    </x-chief::callout>
@else
    <x-chief::callout data-slot="form-group" variant="red" title="Versie verwijderen">
        <x-slot name="icon">
            <x-chief::icon.solid.alert />
        </x-slot>

        <div class="space-y-2">
            <p>Opgelet. De inhoud van deze versie wordt volledig verwijderd.</p>

            <div>
                <x-chief::button variant="outline-red" x-on:click="$wire.deleteItem()">
                    <x-chief::icon.delete />
                    <span>Verwijder versie</span>
                </x-chief::button>
            </div>
        </div>
    </x-chief::callout>
@endif
