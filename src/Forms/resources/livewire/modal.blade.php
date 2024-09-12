<x-chief::dialog wired size="xxs">
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
            <button wire:click="close" type="button" class="shrink-0">
                <x-chief-table-new::button>Annuleer</x-chief-table-new::button>
            </button>

            <button wire:click="save" type="button" class="shrink-0">
                <x-chief-table-new::button color="primary">
                    {{ $this->getButton() }}
                </x-chief-table-new::button>
            </button>
        </x-slot>
    @endif
</x-chief::dialog>