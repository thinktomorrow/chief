<x-chief::dialog.drawer
    wired
    size="sm"
    :title="$addingSites ? 'Voeg links toe' : 'Bewerk links'"
    :edge-to-edge="!$addingSites"
>
    @if ($isOpen)
        @if ($addingSites)
            @include('chief-sites::_partials.adding-sites')
        @else
            @include('chief-sites::site-links..editing-sites')
        @endif
    @endif
</x-chief::dialog.drawer>
