<x-chief::dialog.drawer wired>
    @if ($isOpen)
        @if ($showCreate)
            @include('chief-form::livewire._partials.locale-toggle')
            @include('chief-fragments::livewire._partials.add-fragment-form')
        @else
            @include('chief-fragments::livewire._partials.add-fragment-selection')
        @endif
    @endif
</x-chief::dialog.drawer>
