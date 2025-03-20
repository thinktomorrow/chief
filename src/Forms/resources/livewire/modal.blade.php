<x-chief::dialog.modal wired size="xxs">
    @if ($isOpen)
        <x-slot name="title">
            {{ $this->getTitle() }}
        </x-slot>

        <x-slot name="subtitle">
            {{ $this->getSubTitle() }}
        </x-slot>

        <div class="space-y-4">
            @if ($this->getContent())
                <div class="prose prose-dark prose-spacing">
                    {!! $this->getContent() !!}
                </div>
            @endif

            @foreach ($this->getFields() as $field)
                @php
                    if ($field instanceof \Thinktomorrow\Chief\Forms\Fields\MultiSelect && ! $field->hasDropdownPosition()) {
                        $field->dropdownPositionStatic();
                    }
                @endphp

                {{ $field }}
            @endforeach
        </div>

        <x-slot name="footer">
            <x-chief::dialog.modal.footer>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
                <x-chief::button wire:click="save" variant="blue">
                    {{ $this->getButton() }}
                </x-chief::button>
            </x-chief::dialog.modal.footer>
        </x-slot>
    @endif
</x-chief::dialog.modal>
