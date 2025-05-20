<x-chief::dialog.drawer wired>
    @if ($isOpen)
        @if ($showCreate)
            @if(count($locales) > 0)
                @include('chief-fragments::livewire._partials.locale-toggle')
            @endif
            @include('chief-fragments::livewire._partials.add-fragment-form')
        @else
            @include('chief-fragments::livewire._partials.add-fragment-selection')
        @endif
    @endif
</x-chief::dialog.drawer>
