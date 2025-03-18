<div class="flex items-center justify-center py-4">
    <div class="mx-auto max-w-2xl space-y-4 text-center">
        <x-chief::icon.search-list class="inline size-10 text-grey-500" />

        <div class="space-y-1">
            <h2 class="font-medium text-grey-950">Geen resultaten gevonden</h2>

            <p class="body text-balance text-sm text-grey-500">
                Deze context is nog leeg.
                <br />
                Voeg hieronder een fragment toe.
            </p>
        </div>

        <div class="flex justify-center">
            <x-chief-table::button x-on:click="$wire.addFragment(-1, '{{ $parentId }}')" size="sm" variant="grey">
                <x-chief::icon.plus-sign />
                <span>Fragment toevoegen</span>
            </x-chief-table::button>
        </div>
    </div>
</div>
