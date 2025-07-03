@php
    $modelBindingType = $getWireModelType() == 'defer' ? 'wire:model' : 'wire:model.' . $getWireModelType();
    $modelBinding = [$modelBindingType => Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName::get($getName($locale ?? null))];
@endphp

<x-chief::form.input.range
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    placeholder="{{ $getPlaceholder($locale ?? null) }}"
    value="{{ $getActiveValue($locale ?? null) }}"
    min="{{ $getMin() ?? null }}"
    max="{{ $getMax() ?? null }}"
    step="{{ $getStep() ?? null }}"
    :autofocus="$hasAutofocus()"
    :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge($modelBinding)"
/>
