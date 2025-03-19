<x-chief::dialog.modal wired size="xs" :title="$addingSites ? 'Voeg links toe' : 'Bewerk links'">
    @if ($isOpen)
        @if ($addingSites)
            @include('chief-sites::_partials.adding-sites')
        @else
            @include('chief-sites::_partials.editing-sites')
        @endif
    @endif
</x-chief::dialog.modal>
