<x-chief::dialog.drawer wired>
    @if ($isOpen)
        <x-slot name="header">
            <x-chief::dialog.drawer.header title="{{ $this->getTitle() }}">
                <x-slot name="backButton">
                    <x-chief::button
                        size="sm"
                        variant="grey"
                        type="button"
                        wire:click="close"
                        class="mt-[0.1875rem] shrink-0"
                    >
                        <x-chief::icon.arrow-left />
                    </x-chief::button>
                </x-slot>

                @if (count($this->getStateKeys()) > 0)
                    @foreach ($this->getStateKeys() as $stateKey)
                        <livewire:chief-wire::state :model="$this->getModel()" :state-key="$stateKey" />
                    @endforeach
                @endif
            </x-chief::dialog.drawer.header>
        </x-slot>

        {{--
            @if ($errors->any())
            <x-chief::callout size="sm" variant="red" class="mt-2">
            @foreach ($errors->all() as $error)
            <p>{{ ucfirst($error) }}</p>
            @endforeach
            </x-chief::callout>
            @endif
        --}}

        @include('chief-form::livewire._partials.locale-toggle')

        @foreach ($this->getComponents() as $field)
            {{ $field }}
        @endforeach

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button
                    class="flex items-center"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    variant="blue"
                    type="button"
                >
                    <span>Bewaren</span>
                    <x-chief::icon.loading wire:loading.delay class="animate-spin" />
                </x-chief::button>
                <x-chief::button wire:click="close" type="button">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
