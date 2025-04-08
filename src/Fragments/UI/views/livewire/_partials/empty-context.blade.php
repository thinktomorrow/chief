<x-chief::empty-state title="Nog geen fragmenten">
    Voeg hieronder een eerste afbeelding, tekst, video, ... toe.
    <x-slot name="actions">
        <x-chief::button x-on:click="$wire.addFragment(-1, '{{ $parentId }}')" size="sm" variant="grey">
            <x-chief::icon.plus-sign />
            <span>Fragment toevoegen</span>
        </x-chief::button>
    </x-slot>
</x-chief::empty-state>
