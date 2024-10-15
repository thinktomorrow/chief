<x-chief::dialog.modal wired size="xxs">
    @if ($isOpen)
        <x-slot name="title">
            {{ $this->getTitle() }}
        </x-slot>

        <x-slot name="subtitle">
            {{ $this->getSubTitle() }}
        </x-slot>

        <div class="space-y-4">
            <div class="prose prose-dark prose-spacing">
                @if ($this->getContent())
                    {!! $this->getContent() !!}
                @endif
            </div>

            @foreach ($this->getFields() as $field)
                {{ $field }}
            @endforeach
        </div>

        <x-slot name="footer">
            <x-chief-table::button wire:click="close" class="shrink-0">Annuleer</x-chief-table::button>
            <x-chief-table::button wire:click="save" variant="blue" class="shrink-0">
                    {{ $this->getButton() }}
            </x-chief-table::button>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
