<tr>
    <td colspan="9999">
        <x-chief::empty-state title="Geen resultaten gevonden">
            <x-slot name="icon">
                <x-chief::icon.search-list />
            </x-slot>

            We konden geen resultaten vinden voor je gekozen filters.
            <br />
            Probeer eens een andere filtering.

            <x-slot name="actions">
                <x-chief::button wire:click="resetFilters()" size="sm" variant="grey">
                    <x-chief::icon.filter-remove />
                    <span>Reset filters</span>
                </x-chief::button>
            </x-slot>
        </x-chief::empty-state>
    </td>
</tr>
