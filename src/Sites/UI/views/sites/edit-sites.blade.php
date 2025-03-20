<x-chief::dialog.drawer
    wired
    size="sm"
    :title="$addingSites ? 'Voeg sites toe' : 'Bewerk sites'"
    :edge-to-edge="!$addingSites"
>
    @if ($isOpen)
        @if ($addingSites)
            @include('chief-sites::_partials.adding-sites')
        @else
            @include('chief-sites::sites.editing-sites')
        @endif
    @endif
</x-chief::dialog.drawer>
