@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<x-chief::form.input.range
    wire:model.change="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    placeholder="{{ $getPlaceholder($locale ?? null) }}"
    value="{{ $getActiveValue($locale ?? null) }}"
    min="{{ $getMin() ?? null }}"
    max="{{ $getMax() ?? null }}"
    step="{{ $getStep() ?? null }}"
    :autofocus="$hasAutofocus()"
    :attributes="$attributes->merge($getCustomAttributes())"
/>
