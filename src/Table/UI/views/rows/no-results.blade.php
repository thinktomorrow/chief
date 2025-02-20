<tr>
    <td colspan="9999">
        <div class="flex items-center justify-center py-16">
            <div class="mx-auto max-w-2xl space-y-4 text-center">
                <x-chief::icon.search-list class="inline size-10 text-grey-500" />

                <div class="space-y-1">
                    <h2 class="font-medium text-grey-950">Geen resultaten gevonden</h2>

                    <p class="body text-balance text-sm text-grey-500">
                        We konden geen resultaten vinden voor je gekozen filters.
                        <br />
                        Probeer eens een andere filtering.
                    </p>
                </div>

                <div class="flex justify-center">
                    <x-chief-table::button wire:click="resetFilters()" size="sm" variant="grey">
                        <x-chief::icon.filter-remove />
                        <span>Reset filters</span>
                    </x-chief-table::button>
                </div>
            </div>
        </div>
    </td>
</tr>
