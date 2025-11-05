<x-chief::dialog.drawer wired>
    @if ($isOpen)
        <x-slot name="header">
            <x-chief::dialog.drawer.header
                :title="$formComponent->getTitle()"
                :subtitle="$formComponent->getDescription()"
            />
        </x-slot>

        @if (count($locales) > 1)
            @include('chief-form::livewire._partials.locale-toggle')
        @endif

        <form id="edit-form" wire:submit.prevent="save">
            @foreach ($this->getComponents() as $childComponent)
                {{ $childComponent->render() }}
            @endforeach
        </form>

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button
                    form="edit-form"
                    wire:loading.attr="disabled"
                    variant="blue"
                    type="submit"
                    class="shrink-0"
                >
                    <span>Bewaren</span>
                    <x-chief::icon.loading wire:loading.delay class="animate-spin" />
                </x-chief::button>
                <x-chief::button wire:click="close" class="shrink-0">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
