<x-chief::form.input.hidden
    wire:ignore
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    :attributes="$attributes
        ->merge($getCustomAttributes())
    ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
/>
