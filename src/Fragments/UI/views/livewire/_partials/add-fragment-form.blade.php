<x-slot name="header">
    <x-chief::dialog.drawer.header>
        <x-slot name="backButton">
            <x-chief-table::button
                size="sm"
                variant="grey"
                type="button"
                wire:click="$set('showCreate', false)"
                class="mt-[0.1875rem] shrink-0"
            >
                <x-chief::icon.arrow-left />
            </x-chief-table::button>
        </x-slot>

        <x-slot name="title">Voeg een fragment toe</x-slot>
    </x-chief::dialog.drawer.header>
</x-slot>

@foreach ($this->getFields() as $field)
    {{ $field }}
@endforeach

<x-slot name="footer" class="flex flex-wrap items-start gap-2">
    <x-chief-table::button wire:click="save" variant="blue" class="shrink-0">Bewaren</x-chief-table::button>
    <x-chief-table::button wire:click="$set('showCreate', false)" class="shrink-0">Annuleer</x-chief-table::button>
</x-slot>
