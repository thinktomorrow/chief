<div class="mx-auto max-w-2xl space-y-4 py-16 text-center">
    <x-chief::icon.search-list class="inline size-10 text-grey-500" />

    <div class="space-y-1">
        <h2 class="font-medium text-grey-950">Hier is nog werk aan de winkel</h2>

        <p class="body text-balance text-sm text-grey-500">
            Je hebt nog geen {{ $resource->getPluralLabel() }} aangemaakt.
        </p>
    </div>

    <div class="flex justify-center">
        @foreach ($this->getPrimaryActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="blue" />
        @endforeach
    </div>
</div>
