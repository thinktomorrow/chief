<x-chief::dialog.drawer wired size="sm" title="Bewerk links">
    @if ($isOpen)
        <div class="space-y-4">
            @include('chief-urls::links._partials.items')
            {{-- @include('chief-urls::links._partials.redirects') --}}
        </div>

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" variant="blue">Bewaren</x-chief::button>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
