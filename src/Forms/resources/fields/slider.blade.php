<x-chief::input.range
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
