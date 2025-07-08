<x-slot name="header">
    <x-chief::dialog.drawer.header title="Nieuw fragment aanmaken">
        <x-slot name="backButton">
            <x-chief::button
                size="sm"
                variant="grey"
                type="button"
                wire:click="$set('showCreate', false)"
                class="mt-[0.1875rem] shrink-0"
            >
                <x-chief::icon.arrow-left />
            </x-chief::button>
        </x-slot>
    </x-chief::dialog.drawer.header>
</x-slot>

@foreach ($this->getFields() as $field)
    {{ $field }}
@endforeach

<x-slot name="footer">
    <x-chief::dialog.drawer.footer>
        <x-chief::button wire:click="save" wire:loading.attr="disabled" variant="blue" type="button">
            <span>Bewaren</span>
            <x-chief::icon.loading wire:loading.delay class="animate-spin" />
        </x-chief::button>
        <x-chief::button wire:click="$set('showCreate', false)" type="button">Annuleer</x-chief::button>
    </x-chief::dialog.drawer.footer>
</x-slot>
