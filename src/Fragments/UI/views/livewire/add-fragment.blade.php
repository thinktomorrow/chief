<x-chief::dialog.drawer wired>
    @if ($isOpen)
        @if ($showCreate)
            @if (count($locales) > 1)
                <div class="mb-6">
                    @include('chief-form::livewire._partials.locale-toggle')
                </div>
            @endif

            @include('chief-fragments::livewire._partials.add-fragment-form')
        @else
            @include('chief-fragments::livewire._partials.add-fragment-selection')
        @endif
    @endif
</x-chief::dialog.drawer>
