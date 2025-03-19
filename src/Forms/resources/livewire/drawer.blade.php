<x-chief::dialog.drawer wired size="sm">
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
                {{ $field }}
            @endforeach
        </div>

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief-table::button wire:click="save" variant="blue" class="shrink-0">
                    {{ $this->getButton() }}
                </x-chief-table::button>
                <x-chief-table::button wire:click="close" class="shrink-0">Annuleer</x-chief-table::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
