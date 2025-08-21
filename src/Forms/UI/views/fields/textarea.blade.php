<x-chief::form.input.textarea
    wire:ignore
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    cols="5"
    rows="5"
    :autofocus="$hasAutofocus()"
    :attributes="$attributes
        ->merge($getCustomAttributes())
    ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
    style="resize: vertical"
>
    {{ $getActiveValue($locale ?? null) }}
</x-chief::form.input.textarea>

@include('chief-form::fields._partials.charactercount')
