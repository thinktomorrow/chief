<div class="flex items-center justify-center py-4">
    <div class="mx-auto max-w-2xl space-y-4 text-center">

        <div class="space-y-1">
            <h2 class="font-medium text-grey-950">Nog geen fragmenten in deze paginaopbouw</h2>

            <p class="body text-balance text-sm text-grey-500">
                Voeg hieronder een eerste fragment toe.
            </p>
        </div>

        <x-chief::button x-on:click="$wire.addFragment(-1, '{{ $parentId }}')" size="sm" variant="grey" class="mx-auto">
            <x-chief::icon.plus-sign />
            <span>Fragment toevoegen</span>
        </x-chief::button>
    </div>
</div>
