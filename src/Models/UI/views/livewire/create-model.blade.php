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
            </x-chief::dialog.drawer.header>
        </x-slot>

        @if ($this->modelAllowsLocaleSelection())
            @include('chief-models::livewire._partials.locale-selection')
        @endif

        @if (count($locales) > 1)
            <div class="mb-6">
                @include('chief-form::livewire._partials.locale-toggle')
            </div>
        @endif

        <form id="create-model-form" wire:submit.prevent="save">
            @foreach ($this->getFields() as $field)
                {{ $field }}
            @endforeach
        </form>

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button
                    class="flex items-center"
                    form="create-model-form"
                    wire:loading.attr="disabled"
                    variant="blue"
                    type="submit"
                >
                    <span>Bewaren</span>
                    <x-chief::icon.loading wire:loading.delay class="animate-spin" />
                </x-chief::button>
                <x-chief::button wire:click="close" type="button">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
