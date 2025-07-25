@php
    $selected = (array) $getValueOrFallback($locale ?? null);
    $selected = $selected[0] ?? null;
@endphp

@if ($selected)
    <x-chief::badge size="sm" variant="grey" class="inline-flex items-start">
        <x-chief::icon.tick class="-m-0.5 size-5" />
    </x-chief::badge>
@else
    <x-chief::badge size="sm" variant="grey" class="inline-flex items-start">
        <x-chief::icon.cancel class="size-4" />
    </x-chief::badge>
@endif
