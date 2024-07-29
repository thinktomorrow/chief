<x-chief::dialog wired size="xl" title="{{ $this->getTitle() }}">
    @if($isOpen)
        <x-slot name="header">
            @if($this->getSubTitle())
                <div class="flex items-center justify-between w-full">
                    <span class="text-lg font-bold">{{ $this->getSubTitle() }}</span>
                </div>
            @endif
        </x-slot>

        <div class="relative space-y-6 todo-tijs">
            @if($this->getContent())
                {!! $this->getContent() !!}
            @endif

            @foreach($this->getFields() as $field)
                {{ $field }}
            @endforeach
        </div>

        <x-slot name="footer">
            <div class="w-full space-y-4">

                <div class="flex justify-between gap-6 max-lg:flex-wrap shrink-0">
                    <div class="flex flex-wrap justify-end gap-3 max-lg:w-full shrink-0">
                        <button wire:click="close" type="button" class="btn btn-grey shrink-0">
                            Annuleren
                        </button>

                        <button wire:click="save" type="button" class="btn btn-primary shrink-0">
                            {{ $this->getButton() }}
                        </button>
                    </div>
                </div>
            </div>
        </x-slot>
    @endif
</x-chief::dialog>
