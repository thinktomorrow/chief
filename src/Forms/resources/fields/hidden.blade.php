<x-chief::input.hidden
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) }}"
    value="{{ $getActiveValue($locale ?? null) }}"
    :attributes="$attributes->merge($getCustomAttributes())"
/>
