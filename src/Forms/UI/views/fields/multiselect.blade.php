<x-chief::multiselect
    wire:ignore
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :options="$getMultiSelectFieldOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
    :dropdown-position="$getDropdownPosition()"
    :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
/>
