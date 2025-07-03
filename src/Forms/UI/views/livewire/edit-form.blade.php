<x-chief::dialog.drawer wired>
    @if ($isOpen)
        <x-slot name="header">
            <x-chief::dialog.drawer.header
                :title="$formComponent->getTitle()"
                :subtitle="$formComponent->getDescription()"
                :badges="[]"
            />
        </x-slot>

        @if(count($locales) > 1)
            @include('chief-form::livewire._partials.locale-toggle')
        @endif

        @foreach ($this->getComponents() as $childComponent)
            {{ $childComponent->render() }}
        @endforeach

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" wire:loading.attr="disabled" variant="blue" type="button"
                                 class="shrink-0">Bewaren
                    <div wire:loading.delay class="shrink-0">
                        <x-chief::icon.loading class="size-4 shrink-0 animate-spin" />
                    </div>
                </x-chief::button>
                <x-chief::button wire:click="close" class="shrink-0">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
